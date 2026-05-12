<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quote_type' => 'required|in:website',
            'website_id' => 'required|exists:websites,id',
            'vrm' => 'required|string|max:7',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'customer_type_id' => 'required|exists:customer_types,id',
            'issue' => 'required|string',
            'email_template_id' => 'required|exists:email_templates,id',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|string|max:4',
            'fuel_type' => 'nullable|string|max:50',
            'engine_size' => 'nullable|string|max:50',
            'engine_code' => 'required|string|max:100',
            'engine_number' => 'nullable|string|max:100',
            'vin' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'number_of_doors' => 'nullable|integer|min:0',
            'seat_capacity' => 'nullable|integer|min:0',
            'wheel_plan' => 'nullable|string|max:50',
            'aspiration' => 'nullable|string|max:50',
            'maximum_bhp' => 'required|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'co2_emissions' => 'nullable|string|max:50',
            'body_type' => 'nullable|string|max:100',
            'engine_type' => 'nullable|string|max:100',
            'mileage' => 'nullable|numeric|min:0',
            'quote_amount' => 'nullable|numeric|min:0',
            'guarantee' => 'nullable|string',
            'delivery_time' => 'nullable|string',
            'offer_type' => 'nullable|string|max:50',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string',
        ];
    }
}
