@extends('admin.layouts.master')
@section('title', 'Create Role | Vogue Technics')
@section('content')
    <section>
        <div class="card">
            <h5 class="card-header">Create Role</h5>
            <div class="card-body">
                <form id="addForm" class="ajax-form" action="{{ route('roles.store') }}" method="POST" data-redirect="{{ url('roles') }}" class="g-3">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Manager" required/>
                        </div>
                    </div>
                    <div class="row">
                        <h4>Role Permissions</h4>
                        <hr>
                        @foreach ($permissions as $parent => $childs)
                            <div class="col-6">
                                <div class="col-12 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="{{ $parent }}" onchange="checkAll(this)">
                                        <label class="form-check-label fw-bold fs-5" for="{{ $parent }}">{{ $parent }}</label>
                                    </div>
                                </div>
                                @foreach ($childs['permissions'] as $permission)
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="custom-control-input" type="checkbox" id="{{ $permission->name }}" name="permissions[]" value="{{ $permission->name }}" data-parent="{{ $parent }}" onchange="updateParentCheckbox(this)">
                                            <label class="form-check-label" for="{{ $permission->name }}">{{ $permission->display_name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                                <hr>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1" id="submitButton">Submit</button>
                            <a href="{{ url('roles') }}" class="btn btn-label-secondary ms-auto">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
<script>
    function checkAll(element) {
        var parent = $(element).attr('id');
        if ($(element).is(':checked')) {
            $('input[data-parent="' + parent + '"]').prop('checked', true);
        } else {
            $('input[data-parent="' + parent + '"]').prop('checked', false);
        }
    }

    function updateParentCheckbox(child) {
        let parentId = child.getAttribute("data-parent");
        let parentCheckbox = document.getElementById(parentId);
        let childCheckboxes = document.querySelectorAll(`input[data-parent='${parentId}']`);

        // Check if all child checkboxes are checked
        let allChecked = Array.from(childCheckboxes).every(checkbox => checkbox.checked);
        parentCheckbox.checked = allChecked;
    }
</script>
@endsection
