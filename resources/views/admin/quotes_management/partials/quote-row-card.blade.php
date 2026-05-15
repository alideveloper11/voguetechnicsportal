@php
    $vehicle = $quote->vehicle;
    $customer = $quote->customer;
    $titleParts = array_filter([
        // $vehicle?->make,
        $vehicle?->model,
        $vehicle?->year,
        $vehicle?->fuel_type,
        $vehicle?->engine_size ? $vehicle->engine_size . ' CC' : null,
    ]);

    $title = !empty($titleParts) ? strtoupper(implode(' ', $titleParts)) : 'QUOTE DETAILS';

    $statusClasses = [
        'web_inquiries' => 'bg-label-warning text-warning',
        'portal_quote' => 'bg-label-info text-info',
        'update_quote' => 'bg-label-primary text-primary',
        'accepted' => 'bg-label-success text-success',
        'archived' => 'bg-label-secondary text-secondary',
        'job_card' => 'bg-label-dark text-dark',
        'sold' => 'bg-label-success text-success',
    ];

    $statusClass = $statusClasses[$quote->status] ?? 'bg-label-secondary text-secondary';
    $vrm = $vehicle?->vrm ?: $quote->vrm ?? 'N/A';
    $shortVin = $vehicle?->vin ? substr($vehicle->vin, -7) : 'N/A';
@endphp

<div class="quote-card my-2">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-grow-1">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 gap-sm-3">
                <div class="d-flex flex-column">
                    <small class="text-muted fw-semibold">Reference No</small>
                    <span class="quote-meta-badge bg-success text-nowrap mt-6">{{ $quote->quote_number }}</span>
                </div>

                <div class="d-flex flex-column">
                    <small class="fw-bold">Created at</small>
                    <span class="quote-card-subtitle mt-6">
                        {{ $quote->created_at->format('d-M-Y H:i') }}
                    </span>
                </div>

                <div class="d-flex flex-column">
                    <small class="fw-bold">{{ $listingType === 'accepted-quotes' ? 'Accepted at' : 'Updated at' }}</small>
                    <span class="quote-card-subtitle mt-6">
                        {{ $listingType === 'accepted-quotes' ? date('d-M-Y H:i', strtotime($quote->accepted_at)) : date('d-M-Y H:i', strtotime($quote->updated_at)) }}
                    </span>
                </div>
            </div>

            <div class="flex-grow" style="min-width: 0;">
                <div class="quote-card-title mb-2 text-truncate" title="{{ $title }}">
                    {{ $title }}
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="quote-chip quote-chip-vrm">
                        <span class="uk-number-plate">
                            <span class="uk-number-plate-country">
                                <img src="{{ asset('assets/gb.svg') }}" alt="UK">
                                <span>UK</span>
                            </span>
                            <span class="uk-number-plate-value">{{ $vrm }}</span>
                        </span>
                    </span>
                    <span class="quote-chip quote-chip-engine" title="Engine Code">
                        <i class="icon-base ti tabler-engine"></i>
                        {{ $vehicle?->engine_code ?: 'No Engine Code' }}
                    </span>
                    <span class="quote-chip quote-chip-vin" title="{{ $vehicle?->vin ? 'VIN: ' . $vehicle->vin : 'No VIN' }}">
                        <i class="icon-base ti tabler-barcode"></i>
                        {{ $shortVin }}
                    </span>
                    <span class="quote-chip quote-chip-color" title="Maximum BHP">
                        <i class="icon-base ti tabler-bolt"></i>
                        {{ $vehicle?->maximum_bhp ?: 'No BHP' }}
                    </span>
                    <button type="button" class="quote-chip quote-city-color border-0 js-city-distance"
                        data-url="{{ route('quotes.quote.city-distance', $quote) }}">
                        <i class="icon-base ti tabler-map-pin"></i>
                        {{ $customer?->city ?: 'No City' }}
                    </button>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                    {{-- <span class="badge rounded-pill {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $quote->status)) }}</span> --}}
                    <span class="badge rounded-pill bg-label-success text-success">{{ $listingType === 'accepted-quotes' ? 'Accepted By' : 'Quoted By' }}</span>
                    <span class="text-body-secondary small">
                        <i class="icon-base ti tabler-user me-1"></i>{{ $listingType === 'accepted-quotes' ? $quote->acceptedByUser?->name : $quote->updatedByUser?->name }}
                    </span>
                    @if ($quote->no_answer === 1)
                        <span class="badge rounded-pill bg-label-danger text-body-danger"> <i class="icon-base ti tabler-alert-triangle me-1"></i> No Answer</span>
                    @endif
                </div>
            </div>
            @if ($listingType !== 'archived-quotes')
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group position-static mt-6 ms-6">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            @if ($listingType === 'updated-quotes')

                                @can('proceed-to-accepted-quote')
                                    <li>
                                        <a class="dropdown-item js-accept-quote" href="javascript:void(0)"
                                            data-url="{{ route('quotes.quote.accept', $quote) }}"
                                            data-quote-number="{{ $quote->quote_number }}"
                                            data-booking-date="{{ optional($quote->booking_date)->format('Y-m-d') }}"
                                            data-table="quoteListingTable">
                                            <i class="icon-base ti tabler-file me-2"></i>Proceed to Accept Quote
                                        </a>
                                    </li>
                                @endcan

                                @can('edit-quotes')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('quotes.quote.edit', ['quote' => $quote, 'mode' => 'edit']) }}">
                                            <i class="icon-base ti tabler-edit me-2"></i>Edit Quote
                                        </a>
                                    </li>
                                @endcan

                                @can('proceed-to-archived-quote')
                                    <li>
                                        <a class="dropdown-item archiveQuote" href="javascript:void(0)" data-url="{{ url('quotes/quote/' . $quote->id . '/archive') }}" data-table="quoteListingTable">
                                            <i class="icon-base ti tabler-archive me-2"></i>Proceed to Archive Quote
                                        </a>
                                    </li>
                                @endcan

                            @elseif ($listingType === 'accepted-quotes')

                                @can('proceed-to-reserve-parking')
                                    <li>
                                        <a class="dropdown-item proceedToReserveParking" href="#">
                                            <i class="icon-base ti tabler-parking-circle me-2"></i>Proceed to Reserve Parking
                                        </a>
                                    </li>
                                @endcan
                                @can('proceed-to-job-card')
                                    <li>
                                        <a class="dropdown-item proceedToJobCard" href="#">
                                            <i class="icon-base ti tabler-file me-2"></i>Proceed to Job Card
                                        </a>
                                    </li>
                                @endcan

                            @endif
                            
                            @if (auth()->user()->hasRole('Admin'))
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-item deleteRow" data-url="{{ route('quotes.quote.destroy', $quote) }}" data-table="quoteListingTable">
                                        <i class="icon-base ti tabler-trash me-2"></i>Delete Quote
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
