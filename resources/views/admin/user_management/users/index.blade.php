@extends('admin.layouts.master')
@section('title', 'Users')
@section('content')
    <section>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="table" id="usersTable">
                    <thead>
                        <tr>
                            <th class="not_include"></th>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            {{-- <th>Is Mechanic</th> --}}
                            <th class="not_include">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modelHeading">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('users.store') }}" method="POST" class="ajax-form" data-datatable="#usersTable">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="remove_profile_image" value="0" data-dropzone-remove-flag="true">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="xxxx@xxx.xx" autocomplete="off" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone" autocomplete="off"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="*******"  autocomplete="off" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="*******" autocomplete="off" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="select2 form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="roles" class="form-label">Roles</label>
                                    <select name="role" id="roles" class="select2 form-select" data-allow-clear="true" data-placeholder="Select Role" required>
                                        <option value=""></option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-6">
                                    <label for="is_mechanic" class="form-label">Is Mechanic</label>
                                    <input type="checkbox" name="is_mechanic" id="is_mechanic" value="1" class="form-check-input" />
                                </div> --}}
                                <div class="col-12">
                                    <label class="form-label">Profile Image</label>
                                    <div class="needsclick dropzone dropzone-upload" id="user-profile-image-dropzone"
                                        data-input-name="profile_image" data-max-files="1"
                                        data-remove-flag-input="remove_profile_image">
                                        <div class="dz-message needsclick">
                                            Drop profile image here or click to upload
                                        </div>
                                        <div class="fallback">
                                            <input name="profile_image" type="file" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="switch">
                                    <input type="checkbox" class="switch-input" id="is_mechanic" name="is_mechanic" value="1" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                        <i class="icon-base ti tabler-check"></i>
                                        </span>
                                        <span class="switch-off">
                                        <i class="icon-base ti tabler-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Is Mechanic</span>
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
        <!-- / Add User Modal -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let tableTitle = document.createElement('h5');
            tableTitle.classList.add('card-title', 'mb-0', 'text-md-start', 'text-center', 'pb-md-0', 'pb-6');
            tableTitle.innerHTML = 'List of Users';

            var dataTable = $('#usersTable').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: "{{ url('/users') }}",
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
                        data: "email"
                    },
                    {
                        data: "phone"
                    },
                    {
                        data: 'roles',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (!data || !data.length) {
                                return '<span class="badge bg-label-secondary rounded-pill">No Role</span>';
                            }

                            return data.map(function(role) {
                                return '<span class="badge bg-label-primary rounded-pill me-1">' + role.name + '</span>';
                            }).join('');
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, full, meta) {
                            let badgeClass = '';
                            if (data === 'active') {
                                badgeClass = 'bg-label-danger';
                            } else if (data === 'inactive') {
                                badgeClass = 'bg-label-success';
                            } else if (data === 'pending') {
                                badgeClass = 'bg-label-warning';
                            }
                            return '<span class="badge rounded-pill ' + badgeClass + '">' + data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                        }
                    },
                    // {
                    //     data: 'is_mechanic',
                    //     render: function(data, type, full, meta) {
                    //         return data ? '<span class="badge bg-label-primary">Yes</span>' : '<span class="badge bg-label-secondary">No</span>';
                    //     }
                    // },
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
                            @can('edit-users')
                                btn += '<a href="javascript:void(0)" class="btn btn-icon btn-text-secondary rounded-pill waves-effect item-edit editUser" title="Edit User" data-id="'+full.id+'"><i class="icon-base ti tabler-pencil"></i></a>';
                            @endcan
                            return btn;
                        }
                    },
                    {
                        targets: 2,
                        responsivePriority: 3,
                        render: function (data, type, full, meta) {
                            var name = full['name'];
                            var email = full['email'];
                            var image = full['profile_image'];
                            var output;
                            let baseUrl = "{{ asset('') }}";

                            if (image) {
                            // For Avatar image
                             output = `<img src="${baseUrl}${image}" alt="Avatar" class="rounded-circle">`;
                            } else {
                            // For Avatar badge
                            var stateNum = Math.floor(Math.random() * 6);
                            var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                            var state = states[stateNum];
                            var initials = (name.match(/\b\w/g) || []).map(char => char.toUpperCase());
                            initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                            output = '<span class="avatar-initial rounded-circle bg-label-' + state + '">' + initials + '</span>';
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
                            '<a href="#" class="text-heading text-truncate"><span class="fw-medium">' +
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
                                @can('create-users')
                                    {
                                        text: '<span class="d-flex align-items-center gap-2"><i class="icon-base ti tabler-plus icon-sm"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>',
                                        className: 'create-new btn btn-primary',
                                        attr: {
                                            'data-bs-toggle': 'modal',
                                            'data-bs-target': '#userModal'
                                        }
                                        // action: function(e, dt, node, config) {
                                        //     window.location.href =
                                        //         '{{ route('users.create') }}';
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

            $('body').on('click', '.editUser', function () {
                // blockUI();
                var user_id = $(this).data('id');
                $.get("{{ url('users') }}" +'/' + user_id +'/edit', function (data) {
                    // $.unblockUI();
                    console.log(data);
                    
                    $('#modelHeading').text("Edit User");
                    $('#userModal').modal('show');
                    const userForm = $('#userModal').find('form');
                    userForm.attr('action', "{{ url('users') }}" + '/' + user_id);
                    userForm.find('input[name="_method"]').remove();
                    userForm.append('<input type="hidden" name="_method" value="PUT">');
                    $('#name').val(data.user.name);
                    $('#email').val(data.user.email);
                    $('#phone').val(data.user.phone);
                    $('#roles').val(data.role).trigger('change');
                    $('#status').val(data.user.status).trigger('change');
                    if(data.user.is_mechanic) {
                        console.log('sdfsfsd');
                        
                        $('#is_mechanic').prop('checked', true);
                    } else {
                        $('#is_mechanic').prop('checked', false);
                    }
                    
                    $('#password, #password_confirmation').attr('required', false);
                    userForm.find('input[name="remove_profile_image"]').val('0');

                    const dropzoneElement = document.getElementById('user-profile-image-dropzone');
                    if (typeof resetDropzoneState === 'function') {
                        resetDropzoneState(dropzoneElement);
                    }
                    if (data.user.profile_image_url && typeof setDropzoneExistingFile === 'function') {
                        setDropzoneExistingFile(dropzoneElement, {
                            name: data.user.profile_image_name || 'Current Image',
                            url: data.user.profile_image_url
                        });
                    }
                })
            });
        });
    </script>
@endsection
