@extends('admin.layouts.master')
@section('title', (isset($emailTemplate) ? 'Edit' : 'Create') . ' Email Template | Vogue Technics')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/summernote/summernote-bs5.min.css') }}">
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card">
                    <h5 class="card-header">{{ isset($emailTemplate) ? 'Edit' : 'Create' }} Email Template</h5>
                    <div class="card-body">
                        @include('admin.email_template_management._form')
                    </div>
                </div>
            </div>

            <!-- Variables -->
            <div class="col-12 col-md-4">
                <div class="card">
                    <h5 class="card-header">Available Variables</h5>
                    <div class="card-body">
                        <ul>
                            <li><code>{{ '{customer_name}' }}</code> - Name of the customer</li>
                            <li><code>{{ '{customer_email}' }}</code> - Email of the customer</li>
                            <li><code>{{ '{customer_phone}' }}</code> - Phone of the customer</li>
                            <li><code>{{ '{customer_address}' }}</code> - Address of the customer</li>
                            <li><code>{{ '{customer_postcode}' }}</code> - Postcode of the customer</li>
                            <!-- Add more variables as needed -->
                        </ul>
                        <h5>Vehicle Information</h5>
                        <ul>
                            <li><code>{{ '{reference_no}' }}</code> - Reference number</li>
                            <li><code>{{ '{vehicle_make}' }}</code> - Make of the vehicle</li>
                            <li><code>{{ '{vehicle_model}' }}</code> - Model of the vehicle</li>
                            <li><code>{{ '{vehicle_year}' }}</code> - Year of the vehicle</li>
                            <li><code>{{ '{mileage}' }}</code> - Mileage</li>
                            <li><code>{{ '{price}' }}</code> - Price</li>
                            <li><code>{{ '{guarantee}' }}</code> - Guarantee</li>
                            <li><code>{{ '{delivery_time}' }}</code> - Delivery Time</li>
                            <li><code>{{ '{offer_type}' }}</code> - Offer Type</li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('assets/vendor/libs/summernote/summernote-bs5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.summernote) {
                $('.summernote-editor').summernote({
                    height: 300,
                    dialogsInBody: true,
                    placeholder: 'Write email template body here...',
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
                    styleTags: [
                        'p',
                        'blockquote',
                        'pre',
                        'h1',
                        'h2',
                        'h3',
                        'h4',
                        'h5',
                        'h6'
                    ],
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
        });
    </script>
@endsection
