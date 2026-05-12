@extends('admin.layouts.master')
@section('title', (isset($quote) ? 'Edit' : 'Create') . ' Quote | Vogue Technics')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs5.min.css') }}">
@endsection
@section('content')
    <section>
        <form id="quoteForm" class="g-3"
            action="{{ isset($quote) ? route('quotes.quote.update', $quote) : route('quotes.quote.store') }}"
            method="POST" data-redirect="{{ isset($quote) ? route('quotes.quote.edit', $quote) : route('quotes.quote.create') }}">
            @csrf
            @if (isset($quote))
                @method('PUT')
            @endif
            <input type="hidden" name="submit_action" id="submit_action" value="save">
            <input type="hidden" name="quote_id" id="quote_id" value="{{ $quote->id ?? '' }}">
            <div class="row">
                <div class="col-12 col-md-8">
                    @include('admin.quotes_management.partials.customer-form', [
                        'quote' => $quote ?? null,
                        'customerTypes' => $customerTypes ?? [],
                        'emailTemplates' => $emailTemplates ?? [],
                    ])

                    @include('admin.quotes_management.partials.vehicle-form', [
                        'quote' => $quote ?? null,
                    ])

                    @include('admin.quotes_management.partials.quotation_value', [
                        'quote' => $quote ?? null,
                        'emailTemplates' => $emailTemplates ?? [],
                    ])
                    
                    @include('admin.quotes_management.partials.follow-up', [
                        'quote' => $quote ?? null,
                    ])

                </div>
                <div class="col-12 col-md-4">
                    @include('admin.quotes_management.partials.vrm-detail', ['vehicle' => $quote->vehicle ?? null])
                </div>

            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-primary me-sm-3 me-1" id="submitButton">
                        {{ isset($quote) ? 'Update' : 'Submit' }}
                    </button>
                    <button type="button" class="btn btn-primary me-sm-3 me-1" id="submitAndEmailButton">
                        {{ isset($quote) ? 'Update and Email' : 'Submit and Email' }}
                    </button>
                    <a href="{{ route('quotes.quote.create') }}" class="btn btn-label-secondary ms-auto">Cancel</a>
                </div>
            </div>
        </form>

        <div class="modal fade" id="quoteEmailPreviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Review Quote Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="preview_quote_id">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="preview_recipient_email">Recipient Email</label>
                                <input type="text" id="preview_recipient_email" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="preview_subject">Subject</label>
                                <input type="text" id="preview_subject" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="preview_body">Body</label>
                                <textarea id="preview_body" class="form-control email-preview-editor" rows="14"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="sendQuoteEmailButton">
                            <span class="send-email-button-text">Send Email</span>
                            <span class="send-email-button-loader d-none ms-2">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="quoteEmailLogModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Email Log Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="quoteEmailLogLoading" class="text-center py-4 d-none">
                            <div class="spinner-border text-primary mb-3" role="status"></div>
                            <p class="mb-0 text-body-secondary">Loading email log...</p>
                        </div>

                        <div id="quoteEmailLogError" class="alert alert-danger d-none mb-0"></div>

                        <div id="quoteEmailLogContent" class="d-none">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Subject</label>
                                    <input type="text" id="emailLogSubject" class="form-control" readonly>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Recipient Email</label>
                                    <input type="text" id="emailLogRecipient" class="form-control" readonly>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Sent At</label>
                                    <input type="text" id="emailLogSentAt" class="form-control" readonly>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Sent By</label>
                                    <input type="text" id="emailLogSender" class="form-control" readonly>
                                </div>
                                {{-- <div class="col-12 col-md-4">
                                    <label class="form-label">Sender Email</label>
                                    <input type="text" id="emailLogSenderEmail" class="form-control" readonly>
                                </div> --}}
                                
                                <div class="col-12">
                                    <label class="form-label">Body</label>
                                    <div id="emailLogBody" class="border rounded p-3 bg-body-white" style="min-height: 220px;"></div>
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
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs5.min.js') }}"></script>
    <script>
        const quoteStorePreviewUrl = "{{ route('quotes.quote.preview-email') }}";
        const quoteVrmSearchUrl = "{{ route('quotes.quote.vrm-search') }}";
        const quoteCreateUrl = "{{ route('quotes.quote.create') }}";
        const quoteSendEmailUrlTemplate = "{{ route('quotes.quote.send-email', ['quote' => '__QUOTE_ID__']) }}";
        const currentQuoteId = "{{ $quote->id ?? '' }}";
        const quoteEmailPreviewModal = new bootstrap.Modal(document.getElementById('quoteEmailPreviewModal'));
        const quoteEmailLogModal = new bootstrap.Modal(document.getElementById('quoteEmailLogModal'));

        function initializeSummernoteEditor(selector, options = {}) {
            if (!$.fn.summernote) {
                return;
            }

            $(selector).summernote({
                height: options.height || 300,
                dialogsInBody: true,
                placeholder: options.placeholder || 'Write email template body here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph', 'left', 'center', 'right']],
                    ['insert', ['link', 'picture', 'table']],
                    ['view', ['codeview']]
                ],
                popover: {
                    image: [
                        ['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    table: [
                        ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
                        ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]
                    ]
                },
                styleTags: ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
                callbacks: {
                    onImageUpload: function(files) {
                        const editor = $(this);

                        Array.from(files).forEach(function(file) {
                            const formData = new FormData();
                            formData.append('image', file);
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                            $.ajax({
                                url: "{{ route('email-templates.upload-image') }}",
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    editor.summernote('insertImage', response.url);
                                },
                                error: function(jqXHR) {
                                    let message = 'Image upload failed.';

                                    if (jqXHR.status === 422 && jqXHR.responseJSON?.errors?.image?.length) {
                                        message = jqXHR.responseJSON.errors.image[0];
                                    }

                                    ShowToast('error', message);
                                }
                            });
                        });
                    }
                }
            });
        }

        $(document).ready(function() {
            initializeSummernoteEditor('.email-preview-editor', {
                height: 360,
                placeholder: 'Review and edit the email before sending...'
            });
        });
    </script>

    <script>
        document.getElementById('vrm_search').addEventListener('input', function(e) {
            let value = e.target.value;

            // Remove non-alphanumeric characters
            value = value.replace(/[^a-zA-Z0-9]/g, '');

            // Convert to uppercase
            value = value.toUpperCase();

            // Limit to 7 characters
            value = value.substring(0, 7);

            e.target.value = value;
            $('#vrm').val(value);
        });

    </script>


    <script>
        function buildQuoteFormData(removeMethodField = false) {
            const form = document.getElementById('quoteForm');
            const formData = new FormData(form);

            if (removeMethodField) {
                formData.delete('_method');
            }

            return formData;
        }

        function showVrmLoader() {
            Swal.fire({
                title: 'Fetching vehicle record...',
                text: 'Please wait while we load the VRM details.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        function hideVrmLoader() {
            Swal.close();
        }

        function clearVehicleInformation() {
            $('#detail_model, #detail_make, #detail_year, #detail_fuel_type, #detail_engine_size, #detail_engine_number, #detail_vin_number, #detail_color, #detail_body_type, #detail_doors, #detail_seating_capacity, #detail_wheel_plan, #detail_aspiration, #detail_maximum_bhp, #detail_transmission, #detail_co2').text('');

            $('#make, #model, #fuel_type, #engine_size, #year, #engine_code, #body_type, #engine_type, #engine_number, #vin, #color, #number_of_doors, #seat_capacity, #wheel_plan, #aspiration, #maximum_bhp, #transmission, #co2_emissions').val('');
        }

        function fillCustomerInformation(customer) {
            $('#name').val(customer?.name || '');
            $('#email').val(customer?.email || '');
            $('#phone').val(customer?.phone || '');
            $('#city').val(customer?.city || '');
            $('#address').val(customer?.address || '');
            $('#customer_type_id').val(customer?.customer_type_id || '').trigger('change');
        }

        function submitQuote(url) {
            const form = document.getElementById('quoteForm');
            const formData = buildQuoteFormData();

            $.ajax({
                url: url || form.action,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    ShowToast('success', response.message);

                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 700);
                    }
                },
                error: function(jqXHR) {
                    if (jqXHR.status === 422 && jqXHR.responseJSON?.errors) {
                        $.each(jqXHR.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                ShowToast('error', message);
                            });
                        });
                        return;
                    }

                    ShowToast('error', jqXHR.responseJSON?.message || 'Unable to save quote.');
                }
            });
        }

        function previewQuoteEmail() {
            const formData = buildQuoteFormData(true);

            if (currentQuoteId) {
                formData.set('quote_id', currentQuoteId);
            }

            $.ajax({
                url: quoteStorePreviewUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#preview_quote_id').val(response.quote_id);
                    $('#preview_recipient_email').val(response.recipient_email || '');
                    $('#preview_subject').val(response.subject || '');
                    $('#preview_body').summernote('code', response.body || '');

                    ShowToast('success', response.message);
                    quoteEmailPreviewModal.show();
                },
                error: function(jqXHR) {
                    if (jqXHR.status === 422 && jqXHR.responseJSON?.errors) {
                        $.each(jqXHR.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                ShowToast('error', message);
                            });
                        });
                        return;
                    }

                    ShowToast('error', jqXHR.responseJSON?.message || 'Unable to prepare quote email.');
                }
            });
        }

        function addFollowUpNote() {
            const quoteNoteInput = $('#followUpInput');
            const QuoteNoteDiv = $('#contactLogBody');
            const quoteNoteInputValue = quoteNoteInput.val();
            console.log(quoteNoteInputValue);
            
            const now = new Date();
            const londonTime = new Date(now.toLocaleString("en-US", {
                timeZone: "Europe/London"
            }));

            const getDateFormat =
                londonTime.getFullYear() + '-' +
                String(londonTime.getMonth() + 1).padStart(2, '0') + '-' +
                String(londonTime.getDate()).padStart(2, '0') + ' ' +
                String(londonTime.getHours()).padStart(2, '0') + ':' +
                String(londonTime.getMinutes()).padStart(2, '0') + ':' +
                String(londonTime.getSeconds()).padStart(2, '0');

            if (quoteNoteInputValue !== '') {
                    QuoteNoteDiv.append(`<li class="timeline-item timeline-item-transparent border-dashed">
                    <span class="timeline-point timeline-point-success"></span>
                    <input type="hidden" name="notes[]" value="${quoteNoteInputValue}">
                    <div class="timeline-event">
                        <div class="timeline-header mb-3">
                            <h6 class="mb-0">Follow Up Note</h6>
                            <small class="text-body-secondary">${getDateFormat}</small>
                        </div>
                        <p class="mb-2">${quoteNoteInputValue}</p>
                        <div class="d-flex justify-content-end flex-wrap gap-2 mb-2">
                            <div class="d-flex flex-wrap align-items-center mb-50">
                                <div>
                                    <small class="text-body-secondary">Noted by : {{ auth()->user()->name }}</small><br>
                                </div>
                            </div>
                        </div>
                    </div>`
                );
                quoteNoteInput.val('');
            } else {
                ShowToast('error', 'Quote note field is empty');
            }
        }

        function updateContactLog() {
            $('#contactLogBody').empty();
            $('input[name="quote_note[quote][]"]').each(function() {
                var value = this.value;

            });
        }

        function setSendEmailButtonLoading(isLoading) {
            const button = $('#sendQuoteEmailButton');

            button.prop('disabled', isLoading);
            button.find('.send-email-button-text').text(isLoading ? 'Sending...' : 'Send Email');
            button.find('.send-email-button-loader').toggleClass('d-none', !isLoading);
        }

        document.getElementById('vrm_search').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchVrm();
            }
        });

        function searchVrm() {
            const value = $('#vrm_search').val().trim();
            if (value !== '') {
                $('#vrm').val(value);
                $.ajax({
                    method: "POST",
                    url: quoteVrmSearchUrl,
                    data: {
                        vrm: value,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        showVrmLoader();
                    },
                    success: function(result) {
                        hideVrmLoader();
                        if (result.success) {
                            $('#detail_model').text(result.data.model || '');
                            $('#detail_make').text(result.data.make || '');
                            $('#detail_year').text(result.data.year || '');
                            $('#detail_fuel_type').text(result.data.fuel || '');
                            $('#detail_engine_size').text(result.data.size || '');
                            $('#detail_engine_number').text(result.data.engine_number || '');
                            $('#detail_vin_number').text(result.data.vin || '');
                            $('#detail_color').text(result.data.color || '');
                            $('#detail_body_type').text(result.data.body_type || '');
                            $('#detail_doors').text(result.data.number_of_doors || '');
                            $('#detail_seating_capacity').text(result.data.seat_capacity || '');
                            $('#detail_wheel_plan').text(result.data.wheel_plan || '');
                            $('#detail_aspiration').text(result.data.aspiration || '');
                            $('#detail_maximum_bhp').text(result.data.maximum_bhp || '');
                            $('#detail_transmission').text(result.data.transmission || '');
                            $('#detail_co2').text(result.data.co2 || '');

                            $('#make').val(result.data.make);
                            $('#model').val(result.data.model);
                            $('#fuel_type').val(result.data.fuel);
                            $('#engine_size').val(result.data.size);
                            $('#year').val(result.data.year);
                            $('#engine_code').val(result.data.engine_code);
                            $('#body_type').val(result.data.body_type);
                            $('#engine_type').val(result.data.engine_type || '');
                            $('#engine_number').val(result.data.engine_number || '');
                            $('#vin').val(result.data.vin || '');
                            $('#color').val(result.data.color || '');
                            $('#number_of_doors').val(result.data.number_of_doors || '');
                            $('#seat_capacity').val(result.data.seat_capacity || '');
                            $('#wheel_plan').val(result.data.wheel_plan || '');
                            $('#aspiration').val(result.data.aspiration || '');
                            $('#maximum_bhp').val(result.data.maximum_bhp || '');
                            $('#transmission').val(result.data.transmission || '');
                            $('#co2_emissions').val(result.data.co2 || '');
                            fillCustomerInformation(result.data.customer || null);
                        } else {
                            clearVehicleInformation();
                            ShowToast('error', result.message);
                        }
                    },
                    error: function(jqXHR) {
                        hideVrmLoader();
                        clearVehicleInformation();
                        ShowToast('error', jqXHR.responseJSON?.message || 'Unable to fetch VRM record.');
                    }
                });
            }
        }

        $('#submitButton').on('click', function() {
            $('#submit_action').val('save');
            submitQuote();
        });

        $('#submitAndEmailButton').on('click', function() {
            $('#submit_action').val('save_and_email');
            var emailTemplateId = $('#email_template_id').val();
            var quoteAmount = $('#quote_amount').val();
            if (!emailTemplateId) {
                ShowToast('error', 'Please select an email template before submitting and emailing.');
                return;
            }
            if (!quoteAmount) {
                ShowToast('error', 'Please enter a quote amount before submitting and emailing.');
                return;
            }

            previewQuoteEmail();
        });

        $('#sendQuoteEmailButton').on('click', function() {
            const quoteId = $('#preview_quote_id').val();

            if (!quoteId) {
                ShowToast('error', 'Quote reference is missing.');
                return;
            }

            setSendEmailButtonLoading(true);

            $.ajax({
                url: quoteSendEmailUrlTemplate.replace('__QUOTE_ID__', quoteId),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    subject: $('#preview_subject').val(),
                    body: $('#preview_body').summernote('code')
                },
                success: function(response) {
                    setSendEmailButtonLoading(false);
                    ShowToast('success', response.message);
                    quoteEmailPreviewModal.hide();

                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 700);
                    }
                },
                error: function(jqXHR) {
                    setSendEmailButtonLoading(false);
                    if (jqXHR.status === 422 && jqXHR.responseJSON?.errors) {
                        $.each(jqXHR.responseJSON.errors, function(field, messages) {
                            $.each(messages, function(index, message) {
                                ShowToast('error', message);
                            });
                        });
                        return;
                    }

                    ShowToast('error', jqXHR.responseJSON?.message || 'Unable to send quote email.');
                }
            });
        });

        $(document).on('click', '.js-view-email-log', function() {
            const url = $(this).data('url');

            $('#quoteEmailLogLoading').removeClass('d-none');
            $('#quoteEmailLogError').addClass('d-none').text('');
            $('#quoteEmailLogContent').addClass('d-none');
            $('#emailLogRecipient, #emailLogSentAt, #emailLogSender, #emailLogSenderEmail, #emailLogSubject').val('');
            $('#emailLogBody').html('');
            quoteEmailLogModal.show();

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#quoteEmailLogLoading').addClass('d-none');
                    $('#quoteEmailLogContent').removeClass('d-none');
                    $('#emailLogRecipient').val(response.data.recipient_email || '');
                    $('#emailLogSentAt').val(response.data.sent_at || '');
                    $('#emailLogSender').val(response.data.sender_name || '');
                    $('#emailLogSenderEmail').val(response.data.sender_email || '');
                    $('#emailLogSubject').val(response.data.subject || '');
                    $('#emailLogBody').html(response.data.body || '<p class="text-muted mb-0">No email content found.</p>');
                },
                error: function(jqXHR) {
                    $('#quoteEmailLogLoading').addClass('d-none');
                    $('#quoteEmailLogError')
                        .removeClass('d-none')
                        .text(jqXHR.responseJSON?.message || 'Unable to load email log details.');
                }
            });
        });

    </script>

@endsection
