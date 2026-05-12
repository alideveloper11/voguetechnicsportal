<div class="card">
    <div class="card-header d-flex align-items-center gap-3 p-3">
        <div class="input-group input-group-merge" style="background: #ffc107 !important;">
            <span class="input-group-text p-0 pe-2" id="basic-addon-search31">
                <img src="{{ asset('assets/gb.svg') }}" width="40px" alt="">
            </span>
            <input type="text" id="vrm_search" class="form-control" style="font-size: 22px;"
                placeholder="Search VRM..." maxlength="7" aria-label="Search..."
                aria-describedby="basic-addon-search31" />
            <span class="input-group-text p-0 pe-2" id="basic-addon-search31">
                <i class="ti tabler-search text-black" style="font-size:36px;"></i>
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="row g-0" id="vrm_body">

            <div class="col-12 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #007238">
                    <i class="ti tabler-car-suv text-white" style="font-size:20px;"></i>
                </div>
                <div style="min-width:0;">
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Model</div>
                    <div class="fw-semibold text-truncate" style="font-size:13px;" id="detail_model"
                        title="{{ $vehicle->model ?? '' }}">{{ $vehicle->model ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-building-factory-2 text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Make</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_make">{{ $vehicle->make ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-calendar-event text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Year</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_year">{{ $vehicle->year ?? '' }}</div>
                </div>
            </div>
            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-gas-station text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Fuel Type</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_fuel_type">{{ $vehicle->fuel_type ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-engine text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Engine Size</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_engine_size">{{ $vehicle->engine_size ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-hash text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Engine Number</div>
                    <div class="fw-semibold font-monospace" style="font-size:12px;" id="detail_engine_number">{{ $vehicle->engine_number ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-barcode text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">VIN Number</div>
                    <div class="fw-semibold font-monospace" style="font-size:12px;" id="detail_vin_number">{{ $vehicle->vin_number ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-palette text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Color</div>
                    <div class="fw-semibold d-flex align-items-center gap-2" style="font-size:13px;">
                        {{-- <span
                            style="width:10px;height:10px;border-radius:50%;background:#C0C0C0;border:1px solid #ccc;display:inline-block;"></span> --}}
                        <span id="detail_color">{{ $vehicle->color ?? '' }}</span>
                    </div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-car-garage text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Body Type</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_body_type">{{ $vehicle->body_type ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-door text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Number of Doors</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_doors">{{ $vehicle->number_of_doors ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-armchair text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Seating Capacity</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_seating_capacity">{{ $vehicle->seat_capacity ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-steering-wheel text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Wheel Plan</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_wheel_plan">{{ $vehicle->wheel_plan ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-wind text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Aspiration</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_aspiration">{{ $vehicle->aspiration ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom border-end">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-bolt text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Maximum BHP</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_maximum_bhp">{{ $vehicle->maximum_bhp ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-settings-2 text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">Transmission</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_transmission">{{ $vehicle->transmission ?? '' }}</div>
                </div>
            </div>

            <div class="col-6 d-flex align-items-center gap-2 p-3 border-bottom">
                <div class="rounded d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                    <i class="ti tabler-leaf text-white" style="font-size:20px;"></i>
                </div>
                <div>
                    <div class="text-uppercase text-muted mb-1"
                        style="font-size:10px;letter-spacing:.05em;font-weight:600;">CO2 Emissions</div>
                    <div class="fw-semibold" style="font-size:13px;" id="detail_co2">{{ $vehicle->co2_emissions ?? '' }} g/km</div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card mt-5">
    <div class="card-header">Email Logs</div>
    <div class="card-body">
        <div class="row">
            @forelse ($quote->emailLogs ?? [] as $log)
                <div class="col-12 col-md-6 mb-3">
                    <div class="rounded d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;flex-shrink:0; background: #088d50">
                        <i class="ti tabler-mail text-white" style="font-size:20px;"></i>
                    </div>
                    <div>
                        <div class="text-uppercase text-muted my-1"
                            style="font-size:10px;letter-spacing:.05em;font-weight:600;">
                            <a href="javascript:void(0);"
                                class="js-view-email-log"
                                data-url="{{ route('quotes.quote.email-log', ['quote' => $quote, 'emailLog' => $log]) }}">
                                View Email
                            </a>
                        </div>
                        <div class="fw-semibold" style="font-size:13px;" id="last_email_sent"> {{ $log->created_at->format('jS M Y, g:i A') }} </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">No email logs found.</p>
                </div>
            @endforelse
            
            

        </div>
    </div>
</div>
