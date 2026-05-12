<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\EmailTemplate;
use App\Models\Quote;
use App\Models\QuoteEmailLog;
use App\Models\QuoteNote;
use App\Models\Vehicle;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Promise\PromiseInterface;

class QuoteService
{
    protected function normalizeVrm(?string $vrm): ?string
    {
        if ($vrm === null) {
            return null;
        }

        $normalized = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $vrm));

        return $normalized !== '' ? $normalized : null;
    }

    public function createQuote(array $data, string $source = 'web'): Quote
    {
        return DB::transaction(function () use ($data, $source) {
            $customer = $this->resolveCustomer($data);
            $vehicle = $this->resolveVehicle($data);
            $defaults = $this->resolveSourceDefaults($source);
            $normalizedVrm = $this->normalizeVrm($data['vrm'] ?? null);

            $quote = Quote::create([
                'quote_number' => $this->generateQuoteNumber(),
                'vrm' => $normalizedVrm,
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'website_id' => $data['website_id'] ?? null,
                'email_template_id' => $data['email_template_id'],
                'quote_amount' => $data['quote_amount'] ?? null,
                'mileage' => $data['mileage'] ?? null,
                'guarantee' => $data['guarantee'] ?? null,
                'delivery_time' => $data['delivery_time'] ?? null,
                'offer_type' => $data['offer_type'] ?? null,
                'status' => $defaults['status'],
                'quote_type' => $defaults['quote_type'],
                'notes' => $data['issue'],
                'email_count' => 0,
                'no_answer' => $data['no_answer'] ?? false,
                'created_by' => $defaults['created_by'] ?? null,
                'accepted_by' => $defaults['accepted_by'] ?? null,
                'accepted_at' => $defaults['accepted_at'] ?? null,
            ]);

            $this->syncNotes($quote, $data['notes'] ?? []);

            return $quote->load(['customer', 'vehicle', 'emailTemplate', 'quoteNotes.creator']);
        });
    }

    public function updateQuote(int $quoteId, array $data): Quote
    {
        return DB::transaction(function () use ($quoteId, $data) {
            $quote = Quote::with(['customer', 'vehicle', 'emailTemplate', 'quoteNotes'])->findOrFail($quoteId);
            $customer = $this->resolveCustomer($data, $quote->customer);
            $vehicle = $this->resolveVehicle($data, $quote->vehicle);
            $normalizedVrm = $this->normalizeVrm($data['vrm'] ?? null);

            $quote->update([
                'vrm' => $normalizedVrm,
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'website_id' => $data['website_id'] ?? null,
                'email_template_id' => $data['email_template_id'],
                'quote_amount' => $data['quote_amount'] ?? null,
                'mileage' => $data['mileage'] ?? null,
                'guarantee' => $data['guarantee'] ?? null,
                'delivery_time' => $data['delivery_time'] ?? null,
                'offer_type' => $data['offer_type'] ?? null,
                'notes' => $data['issue'],
                'no_answer' => $data['no_answer'] ?? false,
                'updated_by' => auth()->id(),
            ]);

            $this->syncNotes($quote, $data['notes'] ?? []);

            return $quote->fresh(['customer', 'vehicle', 'emailTemplate', 'quoteNotes.creator']);
        });
    }

    public function deleteQuote(int $quoteId): void
    {
        DB::transaction(function () use ($quoteId) {
            $quote = Quote::with(['customer', 'vehicle', 'quoteNotes', 'emailLogs'])->findOrFail($quoteId);
            $customer = $quote->customer;
            $vehicle = $quote->vehicle;

            $quote->quoteNotes()->delete();
            $quote->emailLogs()->delete();
            $quote->delete();

            if ($customer && ! Quote::where('customer_id', $customer->id)->exists()) {
                $customer->delete();
            }

            if ($vehicle && ! Quote::where('vehicle_id', $vehicle->id)->exists()) {
                $vehicle->delete();
            }
        });
    }

    public function renderQuoteEmail(Quote $quote): array
    {
        $template = $quote->emailTemplate ?? EmailTemplate::findOrFail($quote->email_template_id);
        $variables = $this->quoteTemplateVariables($quote);

        return [
            'recipient_email' => $quote->customer?->email,
            'subject' => $template->renderSubject($variables),
            'body' => $template->renderBody($variables),
        ];
    }

    public function sendQuoteEmail(Quote $quote, string $subject, string $body, int $sentBy): void
    {
        $quote->loadMissing('customer');

        Mail::html($body, function ($message) use ($quote, $subject) {
            $message->to($quote->customer->email, $quote->customer->name)->subject($subject);
        });

        QuoteEmailLog::create([
            'quote_id' => $quote->id,
            'recipient_email' => $quote->customer->email,
            'subject' => $subject,
            'body' => $body,
            'sent_by' => $sentBy,
        ]);

        $quote->increment('email_count');
    }

    protected function customerPayload(array $data): array
    {
        return [
            'customer_type_id' => $data['customer_type_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'address' => $data['address'],
        ];
    }

    protected function resolveCustomer(array $data, ?Customer $currentCustomer = null): Customer
    {
        $payload = $this->customerPayload($data);

        $customer = null;

        if (! empty($payload['email'])) {
            $customer = Customer::where('email', $payload['email'])->first();
        }

        if (! $customer && ! empty($payload['phone'])) {
            $customer = Customer::where('phone', $payload['phone'])->first();
        }

        if (! $customer && $currentCustomer) {
            $customer = $currentCustomer;
        }

        if (! $customer) {
            $customer = Customer::create($payload);
        } else {
            $customer->update($payload);
        }

        return $customer;
    }

    protected function vehiclePayload(array $data): array
    {
        return [
            'vrm' => $this->normalizeVrm($data['vrm'] ?? null),
            'make' => $data['make'],
            'model' => $data['model'],
            'year' => $data['year'],
            'vin' => $data['vin'] ?? null,
            'fuel_type' => $data['fuel_type'] ?? null,
            'engine_size' => $data['engine_size'] ?? null,
            'engine_code' => $data['engine_code'],
            'engine_number' => $data['engine_number'] ?? null,
            'engine_type' => $data['engine_type'] ?? null,
            'maximum_bhp' => $data['maximum_bhp'],
            'color' => $data['color'] ?? null,
            'body_type' => $data['body_type'] ?? null,
            'number_of_doors' => $data['number_of_doors'] ?? null,
            'seat_capacity' => $data['seat_capacity'] ?? null,
            'wheel_plan' => $data['wheel_plan'] ?? null,
            'aspiration' => $data['aspiration'] ?? null,
            'transmission' => $data['transmission'] ?? null,
            'co2_emissions' => $data['co2_emissions'] ?? null,
        ];
    }

    protected function resolveVehicle(array $data, ?Vehicle $currentVehicle = null): Vehicle
    {
        $payload = $this->vehiclePayload($data);

        $vehicle = null;

        if (! empty($payload['vrm'])) {
            $vehicle = Vehicle::where('vrm', $payload['vrm'])->first();
        }

        if (! $vehicle && ! empty($payload['vin'])) {
            $vehicle = Vehicle::where('vin', $payload['vin'])->first();
        }

        if (! $vehicle && ! empty($payload['engine_number'])) {
            $vehicle = Vehicle::where('engine_number', $payload['engine_number'])->first();
        }

        if (! $vehicle && $currentVehicle) {
            $vehicle = $currentVehicle;
        }

        if (! $vehicle) {
            $vehicle = Vehicle::create($payload);
        } else {
            $vehicle->update($payload);
        }

        return $vehicle;
    }

    protected function resolveSourceDefaults(string $source): array
    {
        return $source === 'api'
            ? ['status' => 'web_inquiries', 'quote_type' => 'website']
            : ['status' => 'accepted', 'quote_type' => 'walking_customer', 'created_by' => auth()->id(), 'accepted_by' => auth()->id(), 'accepted_at' => now()];
    }

    protected function generateQuoteNumber(): string
    {
        $nextId = (Quote::max('id') ?? 0) + 1;

        return 'QT-' . now()->format('Ymd') . '-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    protected function syncNotes(Quote $quote, array $notes, bool $replaceExisting = false): void
    {
        if ($replaceExisting) {
            $quote->quoteNotes()->delete();
        }

        foreach (array_filter($notes) as $note) {
            QuoteNote::create([
                'quote_id' => $quote->id,
                'note' => $note,
                'created_by' => auth()->id(),
            ]);
        }
    }

    protected function quoteTemplateVariables(Quote $quote): array
    {
        $quote->loadMissing(['customer', 'vehicle']);

        return [
            'reference_no' => $quote->quote_number,
            'quote_number' => $quote->quote_number,
            'mileage' => $quote->mileage !== null ? number_format((float) $quote->mileage) : '',
            'price' => $quote->quote_amount !== null ? number_format((float) $quote->quote_amount, 2) : '',
            'amount' => $quote->quote_amount !== null ? number_format((float) $quote->quote_amount, 2) : '',
            // 'cityPostcode' => $quote->customer?->city ?? '',
            'customer_name' => $quote->customer?->name ?? '',
            'customer_email' => $quote->customer?->email ?? '',
            'customer_phone' => $quote->customer?->phone ?? '',
            'customer_address' => $quote->customer?->address ?? '',
            'customer_postcode' => $quote->customer?->city ?? '',
            // 'name' => $quote->customer?->name ?? '',
            // 'email' => $quote->customer?->email ?? '',
            // 'phone' => $quote->customer?->phone ?? '',
            // 'address' => $quote->customer?->address ?? '',
            // 'city' => $quote->customer?->city ?? '',
            'vehicle_make' => $quote->vehicle?->make ?? '',
            'vehicle_model' => $quote->vehicle?->model ?? '',
            'vehicle_year' => $quote->vehicle?->year ?? '',
            'vrm' => $quote->vehicle?->vrm ?? '',
            'make' => $quote->vehicle?->make ?? '',
            'model' => $quote->vehicle?->model ?? '',
            'year' => $quote->vehicle?->year ?? '',
            'fuel_type' => $quote->vehicle?->fuel_type ?? '',
            'engine_size' => $quote->vehicle?->engine_size ?? '',
            'engine_code' => $quote->vehicle?->engine_code ?? '',
            'engine_number' => $quote->vehicle?->engine_number ?? '',
            'vin' => $quote->vehicle?->vin ?? '',
            'color' => $quote->vehicle?->color ?? '',
            'body_type' => $quote->vehicle?->body_type ?? '',
            'transmission' => $quote->vehicle?->transmission ?? '',
            'co2_emissions' => $quote->vehicle?->co2_emissions ?? '',
            'issue' => $quote->notes ?? '',
            'guarantee' => strtoupper(str_replace('_', ' ', $quote->guarantee)) ?? '',
            'delivery_time' => strtoupper(str_replace('_', ' ', $quote->delivery_time)) ?? '',
            'offer_type' => strtoupper(str_replace('_', ' ', $quote->offer_type)) ?? '',
            'footer_year' => now()->year,
        ];
    }

    public static function getVrmSearchApi(Request $request): PromiseInterface|Response
    {
        return Http::get('https://uk1.ukvehicledata.co.uk/api/datapackage/VehicleData', [
            'key_vrm' => $request->get('vrm'),
            'auth_apikey' => 'f0860395-aff1-422f-9e75-8a44f7b20b4d',
        ]);
    }

    public static function getVrmSearch(Request $request): array
    {
        $vrm = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $request->get('vrm')));

        $latestQuote = Quote::with(['customer', 'vehicle'])->where('vrm', $vrm)->latest('id')->first();

        if ($latestQuote && $latestQuote->vehicle) {
            $vehicle = $latestQuote->vehicle;

            return [
                'StatusCode' => 'Success',
                'StatusMessage' => 'Quote found in local database.',
                'data' => [
                    'make' => $vehicle->make,
                    'model' => $vehicle->model,
                    'fuel' => $vehicle->fuel_type,
                    'size' => $vehicle->engine_size,
                    'year' => $vehicle->year,
                    'engine_code' => $vehicle->engine_code,
                    'engine_number' => $vehicle->engine_number,
                    'vin' => $vehicle->vin,
                    'color' => $vehicle->color,
                    'body_style' => $vehicle->body_style,
                    'number_of_doors' => $vehicle->number_of_doors,
                    'seat_capacity' => $vehicle->seat_capacity,
                    'wheel_plan' => $vehicle->wheel_plan,
                    'aspiration' => $vehicle->aspiration,
                    'maximum_bhp' => $vehicle->maximum_bhp,
                    'transmission' => $vehicle->transmission,
                    'combine_transmission' => '',
                    'wheel_plan_desc' => '',
                    'co2' => $vehicle->co2_emissions,
                    'body_type' => $vehicle->body_type,
                    'engine_type' => $vehicle->engine_type,
                    'customer' => [
                        'name' => $latestQuote?->customer?->name,
                        'email' => $latestQuote?->customer?->email,
                        'phone' => $latestQuote?->customer?->phone,
                        'city' => $latestQuote?->customer?->city,
                        'address' => $latestQuote?->customer?->address,
                        'customer_type_id' => $latestQuote?->customer?->customer_type_id,
                    ],
                ],
            ];
        }

        $data = [];
        $response = self::getVrmSearchApi($request);
        $responseData = $response->json();
        $StatusCode = $responseData['Response']['StatusCode'];
        $StatusMessage = $responseData['Response']['StatusMessage'];
        if ($StatusCode === 'Success') {

            $vehicleRegistration = $responseData['Response']['DataItems']['VehicleRegistration'];
            $SmmtDetails = $responseData['Response']['DataItems']['SmmtDetails'];
            $CodeList = $responseData['Response']['DataItems']['TechnicalDetails']['General']['Engine']['Code']['CodeList'][0] ?? [];
            $engine = $responseData['Response']['DataItems']['TechnicalDetails']['General']['Engine'];
            $performance = $responseData['Response']['DataItems']['TechnicalDetails']['Performance'];

            $data = [
                'make' => $vehicleRegistration['Make'],
                'model' => $vehicleRegistration['MakeModel'],
                'fuel' => $vehicleRegistration['FuelType'],
                'size' => $vehicleRegistration['EngineCapacity'],
                'year' => $vehicleRegistration['YearOfManufacture'],
                'engine_code' => isset($CodeList['EngineCode']) ? $CodeList['EngineCode'] : $engine['EngineCode'] ?? '',
                'engine_number' => $vehicleRegistration['EngineNumber'],
                'vin' => $vehicleRegistration['Vin'],
                'color' => $vehicleRegistration['Colour'],
                'body_style' => $SmmtDetails['BodyStyle'],
                'number_of_doors' => $SmmtDetails['NumberOfDoors'],
                'seat_capacity' => $vehicleRegistration['SeatingCapacity'],
                'wheel_plan' => $vehicleRegistration['WheelPlan'],
                'aspiration' => $engine['Aspiration'],
                'maximum_bhp' => isset($CodeList['TechPowerBhp']) ? $CodeList['TechPowerBhp'] : $performance['Power']['Bhp'] ?? '',
                'transmission' => $vehicleRegistration['Transmission'],
                'co2' => $performance['Co2'],
                'body_type' => isset($CodeList['BodyType']) ? $CodeList['BodyType'] : '',
            ];
        }
        return [
            'StatusCode' => $StatusCode,
            'StatusMessage' => $StatusMessage,
            'data' => $data,
        ];
    }

}
