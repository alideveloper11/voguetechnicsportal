@extends('admin.layouts.master')
@section('title', 'Websites | Vogue Technics')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="websitesTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Slug</th>
                            {{-- <th>URL</th>
                            <th>Email</th> --}}
                            <th>Phone</th>
                            <th>Landline</th>
                            {{-- <th>Logo</th> --}}
                            <th>Status</th>
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Add Website Modal -->
        <div class="modal fade" id="websiteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Add Website</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('websites.store') }}" method="POST" class="ajax-form"
                        data-datatable="#websitesTable">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="remove_logo" value="0" data-dropzone-remove-flag="true">
                            <div class="row g-3">
                                {{-- <div class="col-12 col-md-4">
                                    <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Enter Code" required />
                                </div> --}}
                                <div class="col-12 col-md-4">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter Name" required />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="url" class="form-label">Url <span class="text-danger">*</span></label>
                                    <input type="text" name="url" id="url" class="form-control"
                                        placeholder="Enter URL" required />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="xxxx@xxx.xx" autocomplete="off" />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Phone" autocomplete="off" />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="landline" class="form-label">Landline</label>
                                    <input type="text" name="landline" id="landline" class="form-control"
                                        placeholder="Landline" autocomplete="off" />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="address" class="form-label">Address</label>
                                    {{-- <textarea name="address" id="address" class="form-control" placeholder="Address" rows="2"></textarea> --}}
                                    <input type="text" name="address" id="address" class="form-control" placeholder="Address" autocomplete="off" />
                                </div>

                                {{-- <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="select2 form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div> --}}
                                
                                <div class="col-12">
                                    <label class="form-label">Logo</label>
                                    <div class="needsclick dropzone dropzone-upload" id="website-logo-dropzone"
                                        data-input-name="logo" data-max-files="1" data-remove-flag-input="remove_logo">
                                        <div class="dz-message needsclick">
                                            Drop Logo here or click to upload
                                        </div>
                                        <div class="fallback">
                                            <input name="logo" type="file" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 p-6">
                                    <label class="switch">
                                    <input type="checkbox" class="switch-input" name="status" value="1" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                        <i class="icon-base ti tabler-check"></i>
                                        </span>
                                        <span class="switch-off">
                                        <i class="icon-base ti tabler-x"></i>
                                        </span>
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
        <!-- / Add Website Modal -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let tableTitle = document.createElement('h5');
            tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
            tableTitle.innerHTML = 'List of Websites';

            var dataTable = $('#websitesTable').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('websites.index') }}",
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
                        data: "slug"
                    },
                    // {
                    //     data: "url"
                    // },
                    // {
                    //     data: "email"
                    // },
                    {
                        data: "phone"
                    },
                    {
                        data: "landline"
                    },
                    // {
                    //     data: 'logo',
                    //     render: function(data, type, full, meta) {
                    //         if (data) {
                    //             return '<img src="' + data +
                    //                 '" alt="Logo" class="img-thumbnail" style="width: 50px; height: 50px;">';
                    //         } else {
                    //             return '<span class="text-muted">No Logo</span>';
                    //         }
                    //     }
                    // },
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
                            return '<span class="badge rounded-pill ' + badgeClass + '">' + statusText + '</span>';
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
                            let targetUrl = "{{ url('websites') }}";

                            let btn = '';
                            @can('edit-websites')
                                btn +=
                                    '<a href="javascript:void(0)" class="btn btn-text-secondary rounded-pill btn-icon item-edit editRow" data-id="' +
                                    full.id + '" data-target-url="' + targetUrl +
                                    '" data-form="#websiteModal form" data-modal="#websiteModal" title="Edit Website"><i class="icon-base ti tabler-pencil"></i></a>';
                            @endcan
                            return btn;
                        }
                    },
                    {
                        targets: 2,
                        responsivePriority: 3,
                        render: function(data, type, full, meta) {
                            var name = full['name'];
                            var email = full['email'];
                            var url = full['url'];
                            var image = full['logo'];
                            var output;
                            let baseUrl = "{{ asset('') }}";

                            if (image) {
                                // For Avatar image
                                output =
                                    `<img src="${image}" alt="Avatar" class="rounded-circle">`;
                            } else {
                                // For Avatar badge
                                var stateNum = Math.floor(Math.random() * 6);
                                var states = ['success', 'danger', 'warning', 'info', 'dark',
                                    'primary', 'secondary'
                                ];
                                var state = states[stateNum];
                                var initials = (name.match(/\b\w/g) || []).map(char => char
                                    .toUpperCase());
                                initials = ((initials.shift() || '') + (initials.pop() || ''))
                                    .toUpperCase();
                                output = '<span class="avatar-initial rounded-circle bg-label-' +
                                    state + '">' + initials + '</span>';
                            }

                            // Creates full output for row
                            var row_output =
                                '<div class="d-flex justify-content-start align-items-center user-name">' +
                                '<div class="avatar-wrapper">' +
                                '<div class="avatar avatar-sm me-4">' +
                                output +
                                '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                '<a href="' + url + '" class="text-heading text-truncate" target="_blank"><span class="fw-medium">' +
                                name +
                                '</span></a>' +
                                '<small>' +
                                email +
                                '</small>' +
                                '</div>' +
                                '</div>';
                            return row_output;
                        }
                    }

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
                                                win.document.body.style.color = config
                                                    .colors.headingColor;
                                                win.document.body.style.borderColor =
                                                    config.colors.borderColor;
                                                win.document.body.style
                                                    .backgroundColor = config.colors
                                                    .bodyBg;
                                                const table = win.document.body
                                                    .querySelector('table');
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
                                @can('create-websites')
                                    {
                                        text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>',
                                        className: 'create-new btn btn-primary',
                                        attr: {
                                            'data-bs-toggle': 'modal',
                                            'data-bs-target': '#websiteModal'
                                        }
                                        // action: function(e, dt, node, config) {
                                        //     window.location.href =
                                        //         '{{ route('websites.create') }}';
                                        // }
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
    </script>
@endsection
