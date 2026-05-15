<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CalendarController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-calendar', only: ['index', 'bookings']),
        ];
    }

    public function index()
    {
        return view('admin.calendar.index');
    }

    public function bookings(Request $request)
    {
        $quotes = Quote::with(['customer', 'vehicle'])
            ->where('status', 'accepted')
            ->whereNotNull('booking_date')
            ->orderBy('booking_date')
            ->get();

        $events = $quotes->map(function (Quote $quote) {
            $vehicle = $quote->vehicle;
            $customer = $quote->customer;
            $vehicleName = trim(collect([
                $vehicle?->make,
                $vehicle?->model,
                $vehicle?->year,
            ])->filter()->implode(' '));

            return [
                'id' => $quote->id,
                'title' => $vehicle?->vrm ?: ($quote->vrm ?: $quote->quote_number),
                'start' => $quote->booking_date?->toIso8601String(),
                'allDay' => false,
                'extendedProps' => [
                    'quote_number' => $quote->quote_number,
                    'booking_date' => $quote->booking_date?->format('d-M-Y H:i'),
                    'customer_name' => $customer?->name,
                    'customer_email' => $customer?->email,
                    'customer_phone' => $customer?->phone,
                    'customer_city' => $customer?->city,
                    'customer_address' => $customer?->address,
                    'vehicle_vrm' => $vehicle?->vrm ?: $quote->vrm,
                    'vehicle_name' => $vehicleName ?: 'Vehicle Information',
                    'vehicle_engine_code' => $vehicle?->engine_code,
                    'vehicle_fuel_type' => $vehicle?->fuel_type,
                    'vehicle_engine_size' => $vehicle?->engine_size,
                    'vehicle_color' => $vehicle?->color,
                    'vehicle_vin' => $vehicle?->vin,
                ],
            ];
        })->values();

        return response()->json($events);
    }
}
