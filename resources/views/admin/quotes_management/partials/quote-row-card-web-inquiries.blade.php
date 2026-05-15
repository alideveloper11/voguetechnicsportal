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

    // $statusClasses = [
    //     'web_inquiries' => 'bg-label-warning text-warning',
    //     'portal_quote' => 'bg-label-info text-info',
    //     'update_quote' => 'bg-label-primary text-primary',
    //     'accepted' => 'bg-label-success text-success',
    //     'archived' => 'bg-label-secondary text-secondary',
    //     'job_card' => 'bg-label-dark text-dark',
    //     'sold' => 'bg-label-success text-success',
    // ];

    // $statusClass = $statusClasses[$quote->status] ?? 'bg-label-secondary text-secondary';
    $vrm = $vehicle?->vrm ?: $quote->vrm ?? 'N/A';
    $shortVin = $vehicle?->vin ? substr($vehicle->vin, -7) : 'N/A';
@endphp

<div class="quote-card my-2">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3 justify-content-between flex-grow-1">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2 gap-sm-3">
                <div class="d-flex flex-column">
                    <small class="text-muted fw-semibold">Reference No</small>
                    <span class="quote-meta-badge bg-success text-nowrap mt-3">{{ $quote->quote_number }}</span>
                </div>

                <div class="d-flex flex-column">
                    <small class="fw-bold">Created at</small>
                    <span class="quote-card-subtitle mt-3">
                        {{ $quote->created_at->format('d-M-Y H:i') }}
                    </span>
                </div>
            </div>

            <div class="flex-grow" style="max-width: 500px;">
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
                    <a href="https://wa.me/{{ $customer->phone }}?text=Hello%20there!" target="_blank" class="quote-chip bg-success border-0 text-white">
                        <i class="icon-base ti tabler-brand-whatsapp"></i>
                        {{ $customer->phone ?: 'No Phone' }}
                    </a>                     
                    <button type="button" class="quote-chip quote-city-color border-0 js-city-distance"
                        data-url="{{ route('quotes.quote.city-distance', $quote) }}">
                        <i class="icon-base ti tabler-map-pin"></i>
                        {{ $customer?->city ?: 'No City' }}
                    </button>
                    <button type="button" class="quote-chip bg-label-success border-0 js-view-detail"
                        data-url="{{ route('quotes.quote.show', $quote) }}">
                        <i class="icon-base ti tabler-eye"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset($quote->website?->logo) }}" class="img-fluid" width="100px" height="80px" alt="{{ $quote->website?->name }}" title="{{ $quote->website?->name }}">
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group position-static ms-6">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        @can('proceed-to-updated-quote')
                            <li>
                                <a class="dropdown-item" href="{{ route('quotes.quote.edit', $quote) }}">
                                    <i class="icon-base ti tabler-edit me-2"></i>Proceed To Updated Quote
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
        </div>

    </div>
</div>
