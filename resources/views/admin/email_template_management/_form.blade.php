<form id="addForm" class="ajax-form g-3"
    action="{{ isset($emailTemplate) ? route('email-templates.update', $emailTemplate) : route('email-templates.store') }}"
    method="POST" data-redirect="{{ url('email-templates') }}">
    @csrf
    @if (isset($emailTemplate))
        @method('PUT')
    @endif

    <div class="row mb-3">
        <div class="col-12 col-md-6">
            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Name"
                value="{{ old('name', $emailTemplate->name ?? '') }}" required />
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
            <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject"
                value="{{ old('subject', $emailTemplate->subject ?? '') }}" required />
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <label class="form-label" for="body">Body <span class="text-danger">*</span></label>
            <textarea id="body" name="body" class="form-control summernote-editor" rows="10" placeholder="Email body" required>{{ old('body', $emailTemplate->body ?? '') }}</textarea>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <label class="switch">
                <input type="checkbox" class="switch-input" id="is_active" name="is_active" value="1"
                    {{ old('is_active', $emailTemplate->is_active ?? 1) ? 'checked' : '' }} />
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

    <div class="row mt-4">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1" id="submitButton">
                {{ isset($emailTemplate) ? 'Update' : 'Submit' }}
            </button>
            <a href="{{ url('email-templates') }}" class="btn btn-label-secondary ms-auto">Cancel</a>
        </div>
    </div>
</form>
