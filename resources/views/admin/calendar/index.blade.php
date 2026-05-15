@extends('admin.layouts.master')
@section('title', 'Calendar | Vogue Technics')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css') }}">
    <style>
        .booking-calendar-card {
            border: 0;
            overflow: hidden;
            /* background:
                radial-gradient(circle at top right, rgba(13, 110, 253, 0.10), transparent 28%),
                linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%); */
            box-shadow: 0 24px 60px rgba(25, 40, 72, 0.08);
        }

        .booking-calendar-card .card-header {
            background: transparent;
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        #bookingCalendar {
            min-height: 760px;
        }

        .booking-calendar-title {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #24324a;
        }

        .booking-calendar-subtitle {
            font-size: 1rem;
            color: #60708c;
        }

        .fc {
            --fc-page-bg-color: transparent;
            --fc-border-color: #d9e2f1;
            --fc-neutral-bg-color: #eff4fb;
            --fc-today-bg-color: rgba(33, 150, 243, 0.08);
            --fc-button-bg-color: #24364d;
            --fc-button-border-color: #24364d;
            --fc-button-hover-bg-color: #1c2a3d;
            --fc-button-hover-border-color: #1c2a3d;
            --fc-button-active-bg-color: #0d6efd;
            --fc-button-active-border-color: #0d6efd;
        }

        .fc .fc-toolbar {
            margin-bottom: 1.35rem !important;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .fc .fc-toolbar-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: #24324a;
            letter-spacing: -0.02em;
        }

        .fc .fc-button {
            text-transform: capitalize;
            border-radius: 12px;
            box-shadow: none !important;
            padding: 0.7rem 1rem;
            font-weight: 700;
        }

        .fc .fc-col-header-cell {
            background: #eef3f9;
            padding: 0.35rem 0;
        }

        .fc .fc-col-header-cell-cushion {
            color: #41526f;
            font-weight: 700;
            text-decoration: none;
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-timegrid-slot-label-cushion,
        .fc .fc-timegrid-axis-cushion {
            color: #5c6b84;
            font-weight: 600;
            text-decoration: none;
        }

        .fc .fc-day-today {
            box-shadow: inset 0 0 0 2px rgba(13, 110, 253, 0.10);
        }

        .fc .fc-daygrid-event,
        .fc .fc-timegrid-event {
            border: 0;
            border-radius: 12px;
            padding: 0;
            background: transparent;
            box-shadow: none;
            padding-inline: 0.25rem !important;
        }

        .booking-event-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            min-height: 34px;
            padding: 0.35rem 0.55rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #0f5bd7 0%, #2b8cff 100%);
            box-shadow: 0 10px 24px rgba(15, 91, 215, 0.24);
            color: #ffffff;
            overflow: hidden;
            cursor: pointer;
        }

        .booking-event-chip-time {
            flex-shrink: 0;
            padding: 0.18rem 0.38rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: #ffffff;
        }

        .booking-event-chip-vrm {
            min-width: 0;
            font-size: 0.77rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fc-timegrid-event .booking-event-chip {
            height: 100%;
            align-items: flex-start;
            flex-direction: column;
        }

        .fc-timegrid-event .booking-event-chip-vrm {
            white-space: normal;
            line-height: 1.2;
        }

        .fc .fc-more-link {
            color: #0d6efd;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .booking-calendar-title {
                font-size: 1.6rem;
            }

            .fc .fc-toolbar-title {
                font-size: 1.2rem;
            }
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="card booking-calendar-card mb-4">
            <div class="card-header border-bottom">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <div>
                        <h4 class="booking-calendar-title mb-1">Bookings Calendar</h4>
                        <p class="booking-calendar-subtitle mb-0">Showing current month bookings by default.</p>
                    </div>
                </div>
            </div>
            <div class="card-body mx-5">
                <div id="bookingCalendar"></div>
            </div>
        </div>

        <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="small text-body-secondary mb-1">Quote Number</div>
                                    <div class="fw-semibold" id="bookingQuoteNumber">-</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="small text-body-secondary mb-1">VRM</div>
                                    <div class="fw-semibold" id="bookingVehicleVrm">-</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="small text-body-secondary mb-1">Booking Date</div>
                                    <div class="fw-semibold" id="bookingDateText">-</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card border-0 bg-body-white">
                                    <div class="card-body">
                                        <h6 class="mb-3">Customer Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="small text-body-secondary mb-1">Name</div>
                                                <div class="fw-semibold" id="bookingCustomerName">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-body-secondary mb-1">Phone</div>
                                                <div class="fw-semibold" id="bookingCustomerPhone">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-body-secondary mb-1">Email</div>
                                                <div class="fw-semibold" id="bookingCustomerEmail">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-body-secondary mb-1">City</div>
                                                <div class="fw-semibold" id="bookingCustomerCity">-</div>
                                            </div>
                                            <div class="col-12">
                                                <div class="small text-body-secondary mb-1">Address</div>
                                                <div class="fw-semibold" id="bookingCustomerAddress">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card border-0 bg-body-white">
                                    <div class="card-body">
                                        <h6 class="mb-3">Vehicle Information</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="small text-body-secondary mb-1">Vehicle</div>
                                                <div class="fw-semibold" id="bookingVehicleName">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-body-secondary mb-1">Engine Code</div>
                                                <div class="fw-semibold" id="bookingEngineCode">-</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-body-secondary mb-1">Fuel Type</div>
                                                <div class="fw-semibold" id="bookingFuelType">-</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-body-secondary mb-1">Engine Size</div>
                                                <div class="fw-semibold" id="bookingEngineSize">-</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-body-secondary mb-1">Color</div>
                                                <div class="fw-semibold" id="bookingColor">-</div>
                                            </div>
                                            <div class="col-12">
                                                <div class="small text-body-secondary mb-1">VIN</div>
                                                <div class="fw-semibold" id="bookingVin">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarElement = document.getElementById('bookingCalendar');
            const bookingDetailsModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));

            if (!calendarElement || typeof Calendar === 'undefined') {
                return;
            }

            const calendar = new Calendar(calendarElement, {
                initialView: 'dayGridMonth',
                plugins: [dayGridPlugin, timegridPlugin],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: "{{ route('calendar.bookings') }}",
                eventDidMount: function(info) {
                    info.el.setAttribute('title', info.event.title);
                },
                eventContent: function(arg) {
                    const timeText = arg.timeText ? `<span class="booking-event-chip-time">${arg.timeText}</span>` : '';

                    return {
                        html: `
                            <div class="booking-event-chip">
                                ${timeText}
                                <span class="booking-event-chip-vrm">${arg.event.title}</span>
                            </div>
                        `
                    };
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventClick: function(info) {
                    const details = info.event.extendedProps || {};

                    $('#bookingQuoteNumber').text(details.quote_number || '-');
                    $('#bookingVehicleVrm').text(details.vehicle_vrm || info.event.title || '-');
                    $('#bookingDateText').text(details.booking_date || '-');
                    $('#bookingCustomerName').text(details.customer_name || '-');
                    $('#bookingCustomerPhone').text(details.customer_phone || '-');
                    $('#bookingCustomerEmail').text(details.customer_email || '-');
                    $('#bookingCustomerCity').text(details.customer_city || '-');
                    $('#bookingCustomerAddress').text(details.customer_address || '-');
                    $('#bookingVehicleName').text(details.vehicle_name || '-');
                    $('#bookingEngineCode').text(details.vehicle_engine_code || '-');
                    $('#bookingFuelType').text(details.vehicle_fuel_type || '-');
                    $('#bookingEngineSize').text(details.vehicle_engine_size || '-');
                    $('#bookingColor').text(details.vehicle_color || '-');
                    $('#bookingVin').text(details.vehicle_vin || '-');

                    bookingDetailsModal.show();
                }
            });

            calendar.render();
        });
    </script>
@endsection
