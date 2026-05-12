@extends('admin.layouts.master')
@section('title', 'Roles | Vogue Technics')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="rolesTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let tableTitle = document.createElement('h5');
            tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
            tableTitle.innerHTML = 'List of Roles';

            var dataTable = $('#rolesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ url('/roles') }}",
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
                            let btn = '';
                            if (full.name != 'Admin') {
                                @can('edit-roles')
                                    btn += '<a href="{{ url('roles') }}' + '/' + full.id +
                                        '/edit" class="btn btn-icon btn-text-secondary rounded-pill waves-effect item-edit" title="Edit Role"><i class="icon-base ti tabler-pencil"></i></a>';
                                @endcan
                            }
                            return btn;
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
                                @can('create-roles')
                                    {
                                        text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>',
                                        className: 'create-new btn btn-primary',
                                        action: function(e, dt, node, config) {
                                            window.location.href =
                                                '{{ route('roles.create') }}';
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
                        // display: DataTable.Responsive.display.modal({
                        //     header: function(row) {
                        //         const data = row.data();
                        //         return 'Details of ' + data['name'];
                        //     }
                        // }),
                        type: 'column',
                        // renderer: function(api, rowIdx, columns) {
                        //     const data = columns
                        //         .map(function(col) {
                        //             return col.title !==
                        //                 '' // Do not show row in modal popup if title is blank (for check box)
                        //                 ?
                        //                 `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                        //             <td>${col.title}:</td>
                        //             <td>${col.data}</td>
                        //         </tr>` :
                        //                 '';
                        //         })
                        //         .join('');

                        //     if (data) {
                        //         const div = document.createElement('div');
                        //         div.classList.add('table-responsive');
                        //         const table = document.createElement('table');
                        //         div.appendChild(table);
                        //         table.classList.add('table');
                        //         table.classList.add('datatables-basic');
                        //         const tbody = document.createElement('tbody');
                        //         tbody.innerHTML = data;
                        //         table.appendChild(tbody);
                        //         return div;
                        //     }
                        //     return false;
                        // }
                    }
                }
            });
        });
    </script>
@endsection
