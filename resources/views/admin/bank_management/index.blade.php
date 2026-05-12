@extends('admin.layouts.master')
@section('title', 'Banks | Vogue Technics')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="banksTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Account Title</th>
                            <th>Account Number</th>
                            <th>Sort Code</th>
                            <th>Website</th>
                            <th>Status</th>
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Add Bank Modal -->
        <div class="modal fade" id="bankModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Add Banks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('banks.store') }}" method="POST" class="ajax-form" data-datatable="#banksTable">
                        <div class="modal-body">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="account_title" class="form-label">Account Title <span class="text-danger">*</span></label>
                                    <input type="text" name="account_title" id="account_title" class="form-control" placeholder="Enter Account Title" required />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Enter Account Number" required />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                    <input type="text" name="branch_name" id="branch_name" class="form-control" placeholder="Enter Branch Name" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="sort_code" class="form-label">Sort Code <span class="text-danger">*</span></label>
                                    <input type="text" name="sort_code" id="sort_code" class="form-control" placeholder="Enter Sort Code" required />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="website_id" class="form-label">Website <span class="text-danger">*</span></label>
                                    <select name="website_id" id="website_id" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Website" required>
                                        <option value=""></option>
                                        @foreach ($websites as $website)
                                            <option value="{{ $website->id }}">{{ $website->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 pt-6">
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input" name="is_vat" id="is_vat" value="1" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"> <i class="icon-base ti tabler-check"></i>
                                            </span>
                                            <span class="switch-off"> <i class="icon-base ti tabler-x"></i>
                                            </span>
                                        </span>
                                        <span class="switch-label">Vat</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-6" id="vatDiv" style="display: none;">
                                    <label for="vat" class="form-label">Vat<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" min="1" max="100" name="vat" id="vat" class="form-control">
                                        <span class="input-group-text"><i class="icon-base ti tabler-percentage"></i></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input" name="status" value="1" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"><i class="icon-base ti tabler-check"></i></span>
                                            <span class="switch-off"><i class="icon-base ti tabler-x"></i></span>
                                        </span>
                                        <span class="switch-label">Status</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- / Add Bank Modal -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let tableTitle = document.createElement('h5');
            tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
            tableTitle.innerHTML = 'List of Banks';

            var dataTable = $('#banksTable').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('banks.index') }}",
                columns: [{
                        data: ''
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "account_title"
                    },
                    {
                        data: "account_number"
                    },
                    {
                        data: "sort_code"
                    },
                    {
                        data: 'website.name',
                        name: 'website.name'
                    },
                    {
                        data: 'status',
                        render: function(data, type, full, meta) {
                            let badgeClass = '';
                            let statusText = '';
                            if (data === 1) {
                                badgeClass = 'bg-label-success';
                                statusText = 'Active';
                            } else if (data === 0) {
                                badgeClass = 'bg-label-warning';
                                statusText = 'Inactive';
                            }
                            return '<span class="badge rounded-pill ' + badgeClass + '">' +
                                statusText + '</span>';
                        }
                    },
                    {
                        data: ''
                    }
                ],
                columnDefs: [{
                        // For Responsive
                        className: 'control',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 2,
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return '';
                        }
                    },
                    {
                        // Actions
                        targets: -1,
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            let targetUrl = "{{ url('banks') }}";

                            let btn = '';
                            @can('edit-banks')
                                btn +=
                                    '<a href="javascript:void(0)" class="btn btn-text-secondary rounded-pill btn-icon item-edit editRow" data-id="' +
                                    full.id + '" data-target-url="' + targetUrl +
                                    '" data-form="#bankModal form" data-modal="#bankModal" title="Edit Bank"><i class="icon-base ti tabler-pencil"></i></a>';
                            @endcan
                            return btn;
                        }
                    },
                ],
                order: [
                    [1, 'desc']
                ],
                layout: {
                    top2Start: {
                        rowClass: 'row card-header flex-column flex-md-row border-bottom mx-0 px-3',
                        features: [tableTitle]
                    },
                    top2End: {
                        features: [{
                            buttons: [{
                                    extend: 'collection',
                                    className: 'btn btn-label-primary dropdown-toggle me-4',
                                    text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-upload icon-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                                    buttons: [{
                                            extend: 'print',
                                            text: '<span class="d-flex align-items-center"><i class="icon-base ti tabler-printer me-1"></i>Print</span>',
                                            exportOptions: {
                                                columns: ':not(.not_include)'
                                            },
                                            customize: function(win) {
                                                win.document.body.style.color = config.colors.headingColor;
                                                win.document.body.style.borderColor = config.colors.borderColor;
                                                win.document.body.style.backgroundColor = config.colors.bodyBg;
                                                const table = win.document.body.querySelector('table');
                                                table.classList.add('compact');
                                                table.style.color = 'inherit';
                                                table.style.borderColor = 'inherit';
                                                table.style.backgroundColor = 'inherit';
                                            }
                                        },
                                        {
                                            extend: 'csv',
                                            text: '<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-text me-1"></i>Csv</span>',
                                            exportOptions: {
                                                columns: ':not(.not_include)'
                                            }
                                        },
                                        {
                                            extend: 'excel',
                                            text: '<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-spreadsheet me-1"></i>Excel</span>',
                                            exportOptions: {
                                                columns: ':not(.not_include)'
                                            }
                                        },
                                        {
                                            extend: 'pdf',
                                            text: '<span class="d-flex align-items-center"><i class="icon-base ti tabler-file-description me-1"></i>Pdf</span>',
                                            exportOptions: {
                                                columns: ':not(.not_include)'
                                            }
                                        },
                                        {
                                            extend: 'copy',
                                            text: '<i class="icon-base ti tabler-copy me-1"></i>Copy',
                                            exportOptions: {
                                                columns: ':not(.not_include)'
                                            }
                                        }
                                    ]
                                },
                                @can('create-banks')
                                    {
                                        text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>',
                                        className: 'create-new btn btn-primary',
                                        attr: {
                                            'data-bs-toggle': 'modal',
                                            'data-bs-target': '#bankModal'
                                        }
                                    }
                                @endcan
                            ]
                        }]
                    },
                    topStart: {
                        rowClass: 'row mx-0 px-3 my-0 justify-content-between border-bottom',
                        features: [{
                            pageLength: {
                                menu: [10, 25, 50, 75, 100],
                                text: 'Show_MENU_entries'
                            }
                        }]
                    },
                    topEnd: {
                        search: {
                            placeholder: ''
                        }
                    },
                    bottomStart: {
                        rowClass: 'row mx-3 justify-content-between',
                        features: ['info']
                    },
                    bottomEnd: 'paging'
                },
                language: {
                    paginate: {
                        next: '<i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>',
                        previous: '<i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>',
                        first: '<i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>',
                        last: '<i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>'
                    }
                },
                responsive: {
                    details: {
                        type: 'column',
                    }
                }
            });
        });

        function toggleVatField() {
            const isCheck = $('#is_vat').is(':checked');
            if (isCheck) {
                $('#vat').attr('required', true);
                $('#vatDiv').show('slow');
            } else {
                $('#vat').attr('required', false);
                $('#vatDiv').hide('slow');
                $('#vat').val('');
            }
        }

        $(document).on('change', '#is_vat', toggleVatField);

        $('#bankModal').on('shown.bs.modal', function () {
            toggleVatField();
        });
    </script>
@endsection
