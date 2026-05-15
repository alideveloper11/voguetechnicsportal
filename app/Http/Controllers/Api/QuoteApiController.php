<?php

namespace App\Http\Controllers\Api;

use App\Models\Website;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreApiQuoteRequest;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;

class QuoteApiController extends Controller
{
    public function store(StoreApiQuoteRequest $request, QuoteService $quoteService): JsonResponse
    {
        $data = $request->validated();
        $data['website_id'] = Website::where('slug', $data['website_slug'])->value('id');
        $quote = $quoteService->createQuote($data, 'api');

        return response()->json([
            'status' => 'success',
            'message' => 'Quote created successfully.',
            'data' => [
                'id' => $quote->id,
                'quote_number' => $quote->quote_number,
                'status' => $quote->status,
                'quote_type' => $quote->quote_type,
                'website_id' => $quote->website_id,
                'vrm' => $quote->vrm,
                'customer' => [
                    'name' => $quote->customer?->name,
                    'email' => $quote->customer?->email,
                    'phone' => $quote->customer?->phone,
                    'city' => $quote->customer?->city,
                    'address' => $quote->customer?->address,
                ],
                'vehicle' => [
                    'make' => $quote->vehicle?->make,
                    'model' => $quote->vehicle?->model,
                    'year' => $quote->vehicle?->year,
                    'engine_code' => $quote->vehicle?->engine_code,
                    'vin' => $quote->vehicle?->vin,
                ],
            ],
        ], 201);
    }
}
