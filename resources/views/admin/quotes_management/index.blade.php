@extends('admin.layouts.master')
@section('title', ($pageTitle ?? 'Quotes') . ' | Vogue Technics')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('custom_css/quote.css') }}">
@endsection

@section('content')
    <section>
        @if (($listingType === 'updated-quotes' || $listingType === 'accepted-quotes') && isset($quoteSummary))
            <div class="row g-4 mb-4">
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 updated-quote-stat-card">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-label-primary me-3">
                                    <i class="icon-base ti tabler-calendar-time text-primary icon-24px"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Quote {{ $listingType === 'updated-quotes' ? 'Updated' : 'Accepted' }} Today</h5>
                                </div>
                            </div>
                            <h3 class="mb-0 text-center">{{ $quoteSummary['today'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 updated-quote-stat-card">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-label-warning me-3">
                                    <i class="icon-base ti tabler-calendar-minus text-warning icon-24px"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Quote {{ $listingType === 'updated-quotes' ? 'Updated' : 'Accepted' }} Yesterday</h5>
                                </div>
                            </div>
                            <h3 class="mb-0 text-center">{{ $quoteSummary['yesterday'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 updated-quote-stat-card">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-label-info me-3">
                                    <i class="icon-base ti tabler-calendar-stats text-info icon-24px"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Quote {{ $listingType === 'updated-quotes' ? 'Updated' : 'Accepted' }} Last 7 Days</h5>
                                </div>
                            </div>
                            <h3 class="mb-0 text-center">{{ $quoteSummary['last_7_days'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header py-3 border-bottom">
                <button type="button" class="quote-filter-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#quoteFiltersCollapse" aria-expanded="false" aria-controls="quoteFiltersCollapse">
                    <span class="h5 card-title mb-0"><i class="icon-base ti tabler-adjustments me-1 text-success icon-24px"></i>Filter Quotes</span>
                    <span class="quote-filter-toggle-icon" id="quoteFiltersToggleIcon">+</span>
                </button>
            </div>
            <div id="quoteFiltersCollapse" class="collapse">
                <div class="card-body pt-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4 col-lg-2">
                            <label class="form-label" for="quoteFilterFromDate">From Date</label>
                            <input type="text" id="quoteFilterFromDate" class="form-control flatpickr-date" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-12 col-md-4 col-lg-2">
                            <label class="form-label" for="quoteFilterToDate">To Date</label>
                            <input type="text" id="quoteFilterToDate" class="form-control flatpickr-date" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="quoteFilterUserId" class="form-label">User</label>
                            <select id="quoteFilterUserId" class="select2 form-select" data-allow-clear="true" data-placeholder="Select User">
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="applyQuoteFilters">Apply Filters</button>
                                <button type="button" class="btn btn-primary" id="downloadExcel"><i class="icon-base ti tabler-file-spreadsheet me-1"></i>Download Excel</button>
                                <button type="button" class="btn btn-label-secondary" id="resetQuoteFilters">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card quote-table-shell">
            <div class="card-header py-3 border-bottom">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h4 class="card-title mb-1">{{ $pageTitle ?? 'Quotes' }}</h4>
                        {{-- <p class="text-body-secondary mb-0">{{ $pageDescription ?? 'Manage quote records.' }}</p> --}}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table quote-listing-table" id="quoteListingTable">
                        <thead class="d-none">
                            <tr>
                                <th>Quote</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cityDistanceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Distance From Vogue Technics</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="cityDistanceLoading" class="text-center py-4">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <p class="mb-0 text-body-secondary">Calculating distance...</p>
                        </div>

                        <div id="cityDistanceError" class="alert alert-danger d-none mb-0"></div>

                        <div id="cityDistanceContent" class="d-none">
                            <div class="mb-3">
                                <div class="text-body-secondary small mb-1">Route</div>
                                <div class="fw-semibold"><span id="distanceFromCity">Vogue Technics</span> to <span id="distanceToCity"></span></div>
                            </div>

                            <div class="mb-3">
                                <div class="text-body-secondary small mb-1">Customer Location</div>
                                <div class="fw-semibold" id="distanceCustomerAddress"></div>
                            </div>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="distance-stat">
                                        <div class="distance-stat-label">Miles</div>
                                        <div class="distance-stat-value" id="distanceMiles">0</div>
                                    </div>
                                </div>
                                {{-- <div class="col-6">
                                    <div class="distance-stat">
                                        <div class="distance-stat-label">Kilometers</div>
                                        <div class="distance-stat-value" id="distanceKm">0</div>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="mt-3">
                                <div class="text-body-secondary small mb-1">Geocoded Location</div>
                                <div class="small" id="distanceMapLabel"></div>
                            </div>

                            <div class="mt-3">
                                <div class="text-body-secondary small mb-2">Map</div>
                                <div id="cityDistanceMap" class="distance-map"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="viewDetailLoading" class="text-center py-4">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <p class="mb-0 text-body-secondary">Fetching details...</p>
                        </div>

                        <div id="viewDetailError" class="alert alert-danger d-none mb-0"></div>

                        <div id="viewDetailContent" class="d-none">

                            <!-- Customer Info Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                            style="width:60px;height:60px;font-size:22px;">
                                            <i class="icon-base ti tabler-user"></i>
                                        </div>

                                        <div class="ms-3">
                                            <h5 class="mb-1 fw-bold" id="customerName"></h5>
                                                <div class="text-body-secondary small">
                                                    Customer Information
                                                </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                                                <div class="small text-body-secondary mb-1">
                                                    Email Address
                                                </div>

                                                <div class="fw-semibold d-flex align-items-center gap-2">
                                                    <i class="icon-base ti tabler-mail text-primary"></i>
                                                    <span id="customerEmail"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                                                <div class="small text-body-secondary mb-1">
                                                    Phone Number
                                                </div>

                                                <div class="fw-semibold d-flex align-items-center gap-2">
                                                    <i class="icon-base ti tabler-phone text-primary"></i>
                                                    <span id="customerPhone"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Issue Card -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">

                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                                            style="width:50px;height:50px;font-size:18px;">
                                            <i class="icon-base ti tabler-message-circle"></i>
                                        </div>

                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold">Customer Issue</h6>
                                            <small class="text-body-secondary">
                                                Detailed issue description
                                            </small>
                                        </div>
                                    </div>

                                    <div class="border rounded-4 p-4 bg-light-subtle">
                                        <div class="lh-lg" id="customerIssue"></div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="acceptQuoteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Proceed To Accept Quote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="acceptQuoteUrl">
                        <div class="mb-3">
                            <div class="text-body-secondary small mb-1">Quote Reference</div>
                            <div class="fw-semibold" id="acceptQuoteReference">-</div>
                        </div>
                        <div>
                            <label for="acceptBookingDate" class="form-label">Datetime Picker</label>
                            <input type="text" class="form-control flatpickr-date-time" placeholder="YYYY-MM-DD HH:MM" id="acceptBookingDate" />
                            {{-- <label class="form-label" for="acceptBookingDate">Booking Date</label>
                            <input type="date" id="acceptBookingDate" class="form-control"> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="acceptQuoteSubmitButton">
                            <span class="accept-quote-button-text">Update</span>
                            <span class="accept-quote-button-loader d-none ms-2">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
    <script>
        $(document).ready(function() {
            const cityDistanceModalElement = document.getElementById('cityDistanceModal');
            const cityDistanceModal = new bootstrap.Modal(cityDistanceModalElement);
            const viewDetailsModalElement = document.getElementById('viewDetailsModal');
            const viewDetailsModal = new bootstrap.Modal(viewDetailsModalElement);
            const acceptQuoteModalElement = document.getElementById('acceptQuoteModal');
            const acceptQuoteModal = new bootstrap.Modal(acceptQuoteModalElement);
            const quoteFiltersCollapseElement = document.getElementById('quoteFiltersCollapse');
            const quoteFiltersToggleIcon = document.getElementById('quoteFiltersToggleIcon');
            let cityDistanceMap = null;
            let londonMarker = null;
            let customerMarker = null;
            let routeLine = null;
            let londonMarkerIcon = null;
            let customerMarkerIcon = null;
            const quoteListingTable = $('#quoteListingTable').DataTable({
                processing: false,
                serverSide: true,
                searching: true,
                lengthChange: true,
                deferRender: true,
                ordering: false,
                pageLength: 10,
                ajax: {
                    url: window.location.href,
                    data: function(d) {
                        d.from_date = $('#quoteFilterFromDate').val();
                        d.to_date = $('#quoteFilterToDate').val();
                        d.user_id = $('#quoteFilterUserId').val();
                    }
                },
                columns: [{
                    data: 'card_html',
                    name: 'quote_number',
                    orderable: false,
                    searchable: true
                }],
                // order: [
                //     [0, 'desc']
                // ],
                createdRow: function(row) {
                    $(row).addClass('align-top');
                },
                language: {
                    search: '',
                    searchPlaceholder: 'Search quote, VRM, customer, engine code...',
                    paginate: {
                        next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
                        previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
                        first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
                        last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
                    }
                },
                layout: {
                    topStart: {
                        features: [{
                            pageLength: {
                                menu: [10, 25, 50, 75, 100],
                                text: 'Show_MENU_entries'
                            }
                        }]
                    },
                    topEnd: {
                        search: {
                            placeholder: 'Search quote, VRM, customer, engine code...'
                        }
                    },
                    bottomStart: {
                        features: ['info']
                    },
                    bottomEnd: 'paging'
                }
            });

            $('#applyQuoteFilters').on('click', function() {
                quoteListingTable.ajax.reload();
            });

            $('#resetQuoteFilters').on('click', function() {
                $('#quoteFilterFromDate, #quoteFilterToDate').val('');
                $('#quoteFilterUserId').val('').trigger('change');
                quoteListingTable.ajax.reload();
            });

            $('#quoteFilterUserId').on('change', function() {
                quoteListingTable.ajax.reload();
            });

            if (quoteFiltersCollapseElement && quoteFiltersToggleIcon) {
                quoteFiltersCollapseElement.addEventListener('show.bs.collapse', function() {
                    quoteFiltersToggleIcon.textContent = '-';
                });

                quoteFiltersCollapseElement.addEventListener('hide.bs.collapse', function() {
                    quoteFiltersToggleIcon.textContent = '+';
                });
            }

            function setAcceptQuoteLoading(isLoading) {
                const button = $('#acceptQuoteSubmitButton');

                button.prop('disabled', isLoading);
                button.find('.accept-quote-button-text').text(isLoading ? 'Updating...' : 'Update');
                button.find('.accept-quote-button-loader').toggleClass('d-none', !isLoading);
            }

            function getMarkerIcon(type) {
                if (typeof L === 'undefined') {
                    return null;
                }

                const iconClass = type === 'london'
                    ? 'distance-map-marker distance-map-marker-london'
                    : 'distance-map-marker distance-map-marker-customer';

                return L.divIcon({
                    className: '',
                    html: `<div class="${iconClass}"></div>`,
                    iconSize: [18, 18],
                    iconAnchor: [9, 9],
                    popupAnchor: [0, -10]
                });
            }

            function drawFallbackLine(fromLatitude, fromLongitude, toLatitude, toLongitude) {
                if (routeLine) {
                    cityDistanceMap.removeLayer(routeLine);
                }

                routeLine = L.polyline([
                    [fromLatitude, fromLongitude],
                    [toLatitude, toLongitude]
                ], {
                    color: '#1f5ea8',
                    weight: 4,
                    opacity: 0.75,
                    dashArray: '8, 8'
                }).addTo(cityDistanceMap);

                cityDistanceMap.fitBounds(routeLine.getBounds(), {
                    padding: [30, 30]
                });
            }

            function drawBestRoute(fromLatitude, fromLongitude, toLatitude, toLongitude) {
                const routeUrl = `https://router.project-osrm.org/route/v1/driving/${fromLongitude},${fromLatitude};${toLongitude},${toLatitude}?overview=full&geometries=geojson`;

                $.ajax({
                    url: routeUrl,
                    type: 'GET',
                    success: function(response) {
                        const coordinates = response?.routes?.[0]?.geometry?.coordinates || [];

                        if (!coordinates.length) {
                            drawFallbackLine(fromLatitude, fromLongitude, toLatitude, toLongitude);
                            return;
                        }

                        const routeLatLngs = coordinates.map(function(coordinate) {
                            return [coordinate[1], coordinate[0]];
                        });

                        if (routeLine) {
                            cityDistanceMap.removeLayer(routeLine);
                        }

                        routeLine = L.polyline(routeLatLngs, {
                            color: '#1f5ea8',
                            weight: 5,
                            opacity: 0.82
                        }).addTo(cityDistanceMap);

                        cityDistanceMap.fitBounds(routeLine.getBounds(), {
                            padding: [30, 30]
                        });
                    },
                    error: function() {
                        drawFallbackLine(fromLatitude, fromLongitude, toLatitude, toLongitude);
                    }
                });
            }

            function renderCityDistanceMap(fromLatitude, fromLongitude, toLatitude, toLongitude, fromTitle, toTitle) {
                if (typeof L === 'undefined') {
                    return;
                }

                if (!cityDistanceMap) {
                    cityDistanceMap = L.map('cityDistanceMap', {
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(cityDistanceMap);

                    londonMarkerIcon = getMarkerIcon('london');
                    customerMarkerIcon = getMarkerIcon('customer');
                }

                if (londonMarker) {
                    cityDistanceMap.removeLayer(londonMarker);
                }

                if (customerMarker) {
                    cityDistanceMap.removeLayer(customerMarker);
                }

                if (routeLine) {
                    cityDistanceMap.removeLayer(routeLine);
                }

                londonMarker = L.marker([fromLatitude, fromLongitude], {
                    icon: londonMarkerIcon || undefined
                }).addTo(cityDistanceMap);
                customerMarker = L.marker([toLatitude, toLongitude], {
                    icon: customerMarkerIcon || undefined
                }).addTo(cityDistanceMap);

                if (fromTitle) {
                    londonMarker.bindPopup(fromTitle);
                }

                if (toTitle) {
                    customerMarker.bindPopup(toTitle).openPopup();
                }

                drawBestRoute(fromLatitude, fromLongitude, toLatitude, toLongitude);

                setTimeout(function() {
                    cityDistanceMap.invalidateSize();
                }, 150);
            }

            cityDistanceModalElement.addEventListener('shown.bs.modal', function() {
                if (cityDistanceMap) {
                    cityDistanceMap.invalidateSize();
                }
            });

            $(document).on('click', '.js-city-distance', function() {
                const url = $(this).data('url');

                $('#cityDistanceLoading').removeClass('d-none');
                $('#cityDistanceError').addClass('d-none').text('');
                $('#cityDistanceContent').addClass('d-none');
                cityDistanceModal.show();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#cityDistanceLoading').addClass('d-none');
                        $('#cityDistanceContent').removeClass('d-none');
                        $('#distanceFromCity').text(response.data.from_city || 'London');
                        $('#distanceToCity').text(response.data.to_city || '');
                        $('#distanceCustomerAddress').text(response.data.customer_address || 'N/A');
                        $('#distanceMiles').text(response.data.distance_miles || 0);
                        $('#distanceKm').text(response.data.distance_km || 0);
                        $('#distanceMapLabel').text(response.data.map_label || '');
                        renderCityDistanceMap(
                            response.data.from_latitude,
                            response.data.from_longitude,
                            response.data.latitude,
                            response.data.longitude,
                            response.data.from_city || 'London',
                            response.data.map_label || response.data.to_city || 'Customer Location'
                        );
                    },
                    error: function(jqXHR) {
                        $('#cityDistanceLoading').addClass('d-none');
                        $('#cityDistanceError')
                            .removeClass('d-none')
                            .text(jqXHR.responseJSON?.message || 'Unable to calculate distance for this customer city.');
                    }
                });
            });

            $(document).on('click', '.js-view-detail', function() {
                const url = $(this).data('url');

                $('#viewDetailLoading').removeClass('d-none');
                $('#viewDetailError').addClass('d-none').text('');
                $('#viewDetailContent').addClass('d-none');
                viewDetailsModal.show();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#viewDetailLoading').addClass('d-none');
                        $('#viewDetailContent').removeClass('d-none');
                        $('#customerName').text(response.data.customer.name || 'N/A');
                        $('#customerEmail').text(response.data.customer.email || 'N/A');
                        $('#customerPhone').text(response.data.customer.phone || 'N/A');
                        $('#customerIssue').text(response.data.notes || 'N/A');
                    },
                    error: function(jqXHR) {
                        $('#viewDetailLoading').addClass('d-none');
                        $('#viewDetailError')
                            .removeClass('d-none')
                            .text(jqXHR.responseJSON?.message || 'Unable to fetch detail for this quote.');
                    }
                });
            });

            $(document).on('click', '.js-accept-quote', function() {
                $('#acceptQuoteUrl').val($(this).data('url'));
                $('#acceptQuoteReference').text($(this).data('quote-number') || '-');
                $('#acceptBookingDate').val($(this).data('booking-date') || '');
                acceptQuoteModal.show();
            });

            $('#acceptQuoteSubmitButton').on('click', function() {
                const url = $('#acceptQuoteUrl').val();
                const bookingDate = $('#acceptBookingDate').val();

                if (!bookingDate) {
                    ShowToast('error', 'Please select a booking date.');
                    return;
                }

                setAcceptQuoteLoading(true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        booking_date: bookingDate
                    },
                    success: function(response) {
                        setAcceptQuoteLoading(false);
                        ShowToast('success', response.message);
                        acceptQuoteModal.hide();
                        $('#quoteListingTable').DataTable().ajax.reload();
                        // if (response.redirect) {
                        //     setTimeout(function() {
                        //         window.location.href = response.redirect;
                        //     }, 700);
                        // }
                    },
                    error: function(jqXHR) {
                        setAcceptQuoteLoading(false);

                        if (jqXHR.status === 422 && jqXHR.responseJSON?.errors) {
                            $.each(jqXHR.responseJSON.errors, function(field, messages) {
                                $.each(messages, function(index, message) {
                                    ShowToast('error', message);
                                });
                            });
                            return;
                        }

                        ShowToast('error', jqXHR.responseJSON?.message || 'Unable to accept quote.');
                    }
                });
            });
        });
    </script>
@endsection
