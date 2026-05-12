<div class="card mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-header">Follow Up Timeline</h5>
        </div>
        <div>
            @if ($quote && $quote->status === 'update_quote')
                <label class="switch switch-success">
                    <input type="checkbox" class="switch-input" id="no_answer" name="no_answer" value="1"
                        {{ old('no_answer', $quote->no_answer ?? 1) ? 'checked' : '' }} />
                    <span class="switch-toggle-slider">
                        <span class="switch-on">
                            <i class="icon-base ti tabler-check"></i>
                        </span>
                        <span class="switch-off">
                            <i class="icon-base ti tabler-x"></i>
                        </span>
                    </span>
                    <span class="switch-label">No Answer</span>
                </label>
            @endif
        </div>
    </div>
    <div class="card-body">
        <ul class="timeline timeline-outline mb-0" id="contactLogBody">
            @foreach (($quote->quoteNotes ?? []) as $note)
                <li class="timeline-item timeline-item-transparent border-dashed">
                    <span class="timeline-point timeline-point-success"></span>
                    <div class="timeline-event">
                        <div class="timeline-header mb-3">
                            <h6 class="mb-0">Follow Up Note</h6>
                            <small class="text-body-secondary">{{ $note->created_at?->format('d M Y, h:i A') }}</small>
                        </div>
                        <p class="mb-2">{{ $note->note }}</p>
                        <input type="hidden" name="existing_note_ids[]" value="{{ $note->id }}">
                        <div class="d-flex justify-content-end flex-wrap gap-2 mb-2">
                            <div class="d-flex flex-wrap align-items-center mb-50">
                                {{-- <div class="avatar avatar-sm me-2">
                                    @if ($note->creator?->profile_image)
                                        <img src="{{ asset('/' . $note->creator->profile_image) }}" alt="Avatar"
                                            class="rounded-circle" />
                                    @else
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar"
                                            class="rounded-circle" />
                                    @endif
                                </div> --}}
                                <div>
                                    <small class="text-body-secondary">Noted by : {{ $note->creator?->name }}</small><br>
                                    {{-- <p class="mb-0 small fw-medium">Noted by : {{ $note->creator?->name }}</p> --}}
                                    {{-- <small>{{ $note->creator?->email }}</small> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="row">
            <div class="col-12 text-center">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Add Follow Up Note" id="followUpInput">
                    <button class="btn btn-primary" type="button" id="addFollowUpBtn" onclick="addFollowUpNote()">
                        <i class="ti tabler-plus"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
