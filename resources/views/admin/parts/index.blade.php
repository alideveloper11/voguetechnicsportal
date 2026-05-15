@extends('admin.layouts.master')
@section('title', 'Parts | Vogue Technics')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="partsTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Add Part Modal -->
        <div class="modal fade" id="partModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Add Part</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('parts.store') }}" method="POST" class="ajax-form"
                        data-datatable="#partsTable">
                        <div class="modal-body">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter Name" required />
                                </div>
                                
                                <div class="col-12">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-control select2" data-placeholder="Select Category">
                                        <option value="">Select Category</option>
                                        <option value="ancillaries">Ancillaries</option>
                                        <option value="consumeables">Consumeables</option>
                                        <option value="cylinder_head">Cylinder Head</option>
                                        <option value="driveline">Driveline</option>
                                        <option value="engine">Engine</option>
                                        <option value="gearboxes">Gearboxes</option>
                                        <option value="engine_component">Other</option>
                                    </select>
                                </div>

                                <div class="col-sm-6 p-2">
                                    <label class="switch">
                                    <input type="checkbox" class="switch-input" name="is_active" value="1" />
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
            tableTitle.innerHTML = 'List of Parts';

            var dataTable = $('#partsTable').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('parts.index') }}",
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
                        data: "category",
                        render: function(data, type, full, meta){
                            return data.replace(/_/g, ' ').toUpperCase();
                        }
                    },
                    {
                        data: "created_by.name",
                        name: "created_by.name",
                    },
                    {
                        data: 'is_active',
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
                            let deleteUrl = '{{ route('parts.destroy', ':id') }}'.replace(':id', full.id);
                            let targetUrl = "{{ url('parts') }}";

                            let btn = '';
                            @can('edit-parts')
                                btn +=
                                    '<a href="javascript:void(0)" class="btn btn-text-secondary rounded-pill btn-icon item-edit editRow" data-id="' +
                                    full.id + '" data-target-url="' + targetUrl +
                                    '" data-form="#partModal form" data-modal="#partModal" title="Edit Part"><i class="icon-base ti tabler-edit"></i></a>';
                            @endcan

                            @can('delete-parts')
                                btn +=
                                    '<a href="javascript:void(0)" class="btn btn-text-danger rounded-pill btn-icon deleteRow" data-url="'+deleteUrl+'" data-table="#partsTable" title="Delete Part"><i class="icon-base ti tabler-trash"></i></a>';
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
                                @can('create-parts')
                                    {
                                        text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>',
                                        className: 'create-new btn btn-primary',
                                        attr: {
                                            'data-bs-toggle': 'modal',
                                            'data-bs-target': '#partModal'
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
    </script>
@endsection
