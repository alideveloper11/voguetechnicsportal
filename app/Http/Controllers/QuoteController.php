<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\User;
use App\Models\QuoteEmailLog;
use App\Models\CustomerType;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Requests\QuoteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\QuoteService;

class QuoteController extends Controller implements HasMiddleware
{
    // protected array $updatedQuoteStatuses = ['portal_quote', 'update_quote', 'accepted'];

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-quotes', only: ['index', 'show', 'webInquiries', 'updatedQuotes', 'acceptedQuotes', 'cityDistance', 'emailLog']),
            new Middleware('permission:create-quotes', only: ['create', 'store']),
            new Middleware('permission:edit-quotes', only: ['edit', 'update']),
            new Middleware('permission:proceed-to-accepted-quote', only: ['acceptQuote']),
            new Middleware('permission:delete-quotes', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->renderQuoteListing(
            Quote::with(['customer', 'vehicle'])->select('quotes.*')->latest('id'),
            'All Quotes',
            'View and manage all quote records.'
        );
    }

    public function webInquiries()
    {
        return $this->renderQuoteListing(
            Quote::with(['customer', 'vehicle', 'website'])
                ->where('status', 'web_inquiries')
                ->select('quotes.*')
                ->latest('id'),
            'Web Inquiries',
            'Quotes received from website inquiries.',
            'web-inquiries'
        );
    }

    public function updatedQuotes()
    {
        $updatedQuoteSummary = [
            'today' => Quote::where('status', 'update_quote')->whereDate('updated_at', now()->toDateString())->count(),
            'yesterday' => Quote::where('status', 'update_quote')->whereDate('updated_at', now()->subDay()->toDateString())->count(),
            'last_7_days' => Quote::where('status', 'update_quote')->whereBetween('updated_at', [
                now()->copy()->subDays(6)->startOfDay(),
                now()->copy()->endOfDay(),
            ])->count(),
        ];

        return $this->renderQuoteListing(
            Quote::with(['customer', 'vehicle', 'acceptedByUser', 'updatedByUser'])
                ->where('status', 'update_quote')
                ->select('quotes.*')
                ->latest('id'),
            'Updated Quotes',
            'Quotes in portal, updated, and accepted states.',
            'updated-quotes',
            [
                'quoteSummary' => $updatedQuoteSummary,
            ]
        );
    }
    
    public function acceptedQuotes()
    {
        $acceptedQuoteSummary = [
            'today' => Quote::where('status', 'accepted')->whereDate('updated_at', now()->toDateString())->count(),
            'yesterday' => Quote::where('status', 'accepted')->whereDate('updated_at', now()->subDay()->toDateString())->count(),
            'last_7_days' => Quote::where('status', 'accepted')->whereBetween('updated_at', [
                now()->copy()->subDays(6)->startOfDay(),
                now()->copy()->endOfDay(),
            ])->count(),
        ];

        return $this->renderQuoteListing(
            Quote::with(['customer', 'vehicle', 'acceptedByUser', 'updatedByUser'])
                ->where('status', 'accepted')
                ->select('quotes.*')
                ->latest('id'),
            'Accepted Quotes',
            'Quotes that have been accepted.',
            'accepted-quotes',
            [
                'quoteSummary' => $acceptedQuoteSummary,
            ]
        );
    }

    public function archivedQuotes()
    {
        return $this->renderQuoteListing(
            Quote::with(['customer', 'vehicle', 'acceptedByUser', 'updatedByUser'])
                ->where('status', 'archived')
                ->select('quotes.*')
                ->latest('id'),
            'Archived Quotes',
            'Quotes that have been archived.',
            'archived-quotes'
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customerTypes = CustomerType::where('is_active', true)->get(['name', 'id']);
        $emailTemplates = EmailTemplate::where('is_active', true)->get(['name', 'id']);
        return view('admin.quotes_management.create', compact('customerTypes', 'emailTemplates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuoteRequest $request, QuoteService $quoteService)
    {
        $data = $request->validated();
        $quote = $quoteService->createQuote($data, 'web');

        $redirect = match ($updatedQuote->status) {
            'accepted' => route('quotes.accepted-quotes'),
            'update_quote' => route('quotes.updated-quotes'),
            default => route('quotes.quote.create'),
        };
        
        return response()->json([
            'status' => 'success',
            'message' => 'Quote created successfully.',
            'quote_id' => $quote->id,
            'redirect' => $redirect,
        ]);
    }

    public function previewEmail(QuoteRequest $request, QuoteService $quoteService)
    {
        $quoteId = $request->input('quote_id');
        $quote = $quoteId
            ? $quoteService->updateQuote((int) $quoteId, $request->validated())
            : $quoteService->createQuote($request->validated(), 'web');
        $preview = $quoteService->renderQuoteEmail($quote);

        return response()->json([
            'status' => 'success',
            'message' => $quoteId
                ? 'Quote updated. Review the email before sending.'
                : 'Quote saved. Review the email before sending.',
            'quote_id' => $quote->id,
            'recipient_email' => $preview['recipient_email'],
            'subject' => $preview['subject'],
            'body' => $preview['body'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        return response()->json([
            'status' => 'success',
            'data' => $quote->load(['customer', 'vehicle']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        $quote->load(['customer', 'vehicle', 'quoteNotes.creator', 'emailLogs.sender']);
        $customerTypes = CustomerType::where('is_active', true)->get(['name', 'id']);
        $emailTemplates = EmailTemplate::where('is_active', true)->get(['name', 'id']);
        $actionMode = request()->get('mode', 'edit');

        return view('admin.quotes_management.create', compact('quote', 'customerTypes', 'emailTemplates', 'actionMode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuoteRequest $request, Quote $quote, QuoteService $quoteService)
    {
        $updatedQuote = $quoteService->updateQuote($quote->id, $request->validated());
        $redirect = match ($updatedQuote->status) {
            'accepted' => route('quotes.accepted-quotes'),
            'update_quote' => route('quotes.updated-quotes'),
            default => route('quotes.quote.edit', ['quote' => $quote, 'mode' => $request->input('action_mode', 'edit')]),
        };

        return response()->json([
            'status' => 'success',
            'message' => 'Quote updated successfully.',
            'quote_id' => $updatedQuote->id,
            'redirect' => $redirect,
        ]);
    }

    public function sendEmail(Request $request, Quote $quote, QuoteService $quoteService)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $quoteService->sendQuoteEmail($quote->load('customer'), $data['subject'], $data['body'], auth()->id());

        return response()->json([
            'status' => 'success',
            'message' => 'Quote email sent successfully.',
            'redirect' => match ($quote->fresh()->status) {
                'accepted' => route('quotes.accepted-quotes'),
                'update_quote' => route('quotes.updated-quotes'),
                default => route('quotes.quote.edit', ['quote' => $quote, 'mode' => 'edit']),
            },
        ]);
    }

    public function acceptQuote(Request $request, Quote $quote, QuoteService $quoteService)
    {
        if ($quote->status !== 'update_quote') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only updated quotes can be moved to accepted.',
            ], 422);
        }

        $data = $request->validate([
            'booking_date' => 'required|date',
        ]);

        $acceptedQuote = $quoteService->acceptQuote($quote->id, $data['booking_date']);

        return response()->json([
            'status' => 'success',
            'message' => 'Quote accepted successfully.',
            'quote_id' => $acceptedQuote->id,
            'redirect' => route('quotes.accepted-quotes'),
        ]);
    }

    public function vrmSearch(Request $request)
    {
        $request->validate([
            'vrm' => 'required|string|max:7',
        ]);

        $result = QuoteService::getVrmSearch($request);

        return response()->json([
            'success' => $result['StatusCode'] === 'Success',
            'message' => $result['StatusMessage'],
            'data' => $result['data'],
        ]);
    }

    public function cityDistance(Quote $quote)
    {
        $quote->loadMissing('customer');

        if (! $quote->customer || ! $quote->customer->city) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer city is not available for this quote.',
            ], 422);
        }

        $customerLocation = $this->lookupLocation($quote->customer->address, $quote->customer->city);

        if (! $customerLocation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to find the customer location on the map service.',
            ], 422);
        }

        $london = [
            'name' => 'RM20 4EL',
            'lat' => 51.47466,
            'lon' => 0.29646,
        ];

        $distanceKm = $this->calculateDistanceKm(
            $london['lat'],
            $london['lon'],
            $customerLocation['lat'],
            $customerLocation['lon']
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'from_city' => $london['name'],
                'from_latitude' => $london['lat'],
                'from_longitude' => $london['lon'],
                'to_city' => $quote->customer->city,
                'customer_name' => $quote->customer->name,
                'customer_address' => trim(($quote->customer->address ? $quote->customer->address . ', ' : '') . $quote->customer->city, ', '),
                'distance_km' => round($distanceKm, 1),
                'distance_miles' => round($distanceKm * 0.621371, 1),
                'latitude' => $customerLocation['lat'],
                'longitude' => $customerLocation['lon'],
                'map_label' => $customerLocation['display_name'] ?? $quote->customer->city,
            ],
        ]);
    }

    public function emailLog(Quote $quote, QuoteEmailLog $emailLog)
    {
        if ((int) $emailLog->quote_id !== (int) $quote->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'The selected email log does not belong to this quote.',
            ], 404);
        }

        $emailLog->loadMissing('sender:id,name,email');

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $emailLog->id,
                'recipient_email' => $emailLog->recipient_email,
                'subject' => $emailLog->subject,
                'body' => $emailLog->body,
                'sent_at' => $emailLog->created_at?->format('jS M Y, g:i A'),
                'sender_name' => $emailLog->sender?->name,
                'sender_email' => $emailLog->sender?->email,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote, QuoteService $quoteService)
    {
        $quoteService->deleteQuote($quote->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Quote deleted successfully.',
        ]);
    }

    public function archiveQuote(Quote $quote, QuoteService $quoteService)
    {
       if(!$quote){
            return response()->json([
                'status' => 'error',
                'message' => 'Quote not found.',
            ], 404);
        }

        $quote->update([
            'status' => 'archived',
            'archived_by' => auth()->id(),
            'archived_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Quote archived successfully.',
        ]);
    }


    protected function renderQuoteListing($query, string $pageTitle, string $pageDescription, string $listingType = 'all', array $viewData = [])
    {
        if (request()->ajax()) {
            return DataTables::eloquent($query, $listingType)
                ->addColumn('card_html', function (Quote $quote) use ($listingType) {
                    if($listingType === 'web-inquiries') {
                        return view('admin.quotes_management.partials.quote-row-card-web-inquiries', [
                            'quote' => $quote,
                            'listingType' => $listingType,
                        ])->render();
                    }
                    return view('admin.quotes_management.partials.quote-row-card', [
                        'quote' => $quote,
                        'listingType' => $listingType,
                    ])->render();
                })
                ->filter(function ($query) use ($listingType) {
                    $searchValue = request('search.value');
                    $fromDate = request('from_date');
                    $toDate = request('to_date');
                    $userId = request('user_id');
                    $dateColumn = $this->resolveListingDateColumn($listingType);

                    if ($fromDate) {
                        $query->whereDate($dateColumn, '>=', $fromDate);
                    }

                    if ($toDate) {
                        $query->whereDate($dateColumn, '<=', $toDate);
                    }

                    if ($userId) {
                        $query->where($this->resolveListingUserColumn($listingType), $userId);
                    }

                    if (! $searchValue) {
                        return;
                    }

                    $query->where(function ($builder) use ($searchValue) {
                        $builder->where('quote_number', 'like', '%' . $searchValue . '%')
                            ->orWhere('status', 'like', '%' . $searchValue . '%')
                            ->orWhereHas('customer', function ($customerQuery) use ($searchValue) {
                                $customerQuery->where('name', 'like', '%' . $searchValue . '%')
                                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                                    ->orWhere('phone', 'like', '%' . $searchValue . '%');
                            })
                            ->orWhereHas('vehicle', function ($vehicleQuery) use ($searchValue) {
                                $vehicleQuery->where('vrm', 'like', '%' . $searchValue . '%')
                                    ->orWhere('make', 'like', '%' . $searchValue . '%')
                                    ->orWhere('model', 'like', '%' . $searchValue . '%')
                                    ->orWhere('engine_code', 'like', '%' . $searchValue . '%')
                                    ->orWhere('color', 'like', '%' . $searchValue . '%');
                            });
                    });
                })
                ->rawColumns(['card_html'])
                ->make(true);
        }

        $users = User::where('status', 'active')->get(['id', 'name']);
        return view('admin.quotes_management.index', array_merge(
            compact('pageTitle', 'pageDescription', 'listingType', 'users'),
            $viewData
        ));
    }

    protected function resolveListingDateColumn(string $listingType): string
    {
        return match ($listingType) {
            'updated-quotes' => 'quotes.updated_at',
            'accepted-quotes' => 'quotes.accepted_at',
            'archived-quotes' => 'quotes.archived_at',
            default => 'quotes.created_at',
        };
    }

    protected function resolveListingUserColumn(string $listingType): string
    {
        return match ($listingType) {
            'updated-quotes' => 'quotes.updated_by',
            'accepted-quotes' => 'quotes.accepted_by',
            'archived-quotes' => 'quotes.archived_by',
            default => 'quotes.created_by',
        };
    }

    protected function lookupLocation(?string $address, string $city): ?array
    {
        $queries = array_filter([
            trim(($address ? $address . ', ' : '') . $city . ', United Kingdom', ', '),
            trim($city . ', United Kingdom', ', '),
            $city,
        ]);

        foreach ($queries as $query) {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'VoguePortal/1.0',
                    'Accept' => 'application/json',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'format' => 'jsonv2',
                    'limit' => 1,
                    'q' => $query,
                ]);

            if (! $response->successful()) {
                continue;
            }

            $location = $response->json()[0] ?? null;

            if ($location && isset($location['lat'], $location['lon'])) {
                return [
                    'lat' => (float) $location['lat'],
                    'lon' => (float) $location['lon'],
                    'display_name' => $location['display_name'] ?? null,
                ];
            }
        }

        return null;
    }

    protected function calculateDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}
