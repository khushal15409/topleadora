@php
    $canAssign = $assignableUsers->isNotEmpty();
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label" for="lead-name">{{ __('Name') }} <span class="text-danger">*</span></label>
        <input
            type="text"
            name="name"
            id="lead-name"
            value="{{ old('name', $lead->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="lead-phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
        <input
            type="text"
            name="phone"
            id="lead-phone"
            value="{{ old('phone', $lead->phone) }}"
            class="form-control @error('phone') is-invalid @enderror"
            required
        >
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="lead-source">{{ __('Source') }} <span class="text-danger">*</span></label>
        <select name="source" id="lead-source" class="form-select @error('source') is-invalid @enderror" required>
            @foreach ($sourceOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('source', $lead->source) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('source')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="lead-status">{{ __('Status') }} <span class="text-danger">*</span></label>
        <select name="status" id="lead-status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $lead->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="lead-followup">{{ __('Next follow-up') }}</label>
        <input
            type="datetime-local"
            name="next_followup_at"
            id="lead-followup"
            value="{{ old('next_followup_at', optional($lead->next_followup_at)?->format('Y-m-d\TH:i')) }}"
            class="form-control @error('next_followup_at') is-invalid @enderror"
        >
        @error('next_followup_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if ($canAssign)
        <div class="col-md-6">
            <label class="form-label" for="lead-assign">{{ __('Assigned to') }}</label>
            <select name="assigned_to" id="lead-assign" class="form-select @error('assigned_to') is-invalid @enderror">
                <option value="">{{ __('— Unassigned —') }}</option>
                @foreach ($assignableUsers as $u)
                    <option value="{{ $u->id }}" @selected((string) old('assigned_to', $lead->assigned_to) === (string) $u->id)>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif
    <div class="col-12">
        <label class="form-label" for="lead-notes">{{ __('Notes') }}</label>
        <textarea
            name="notes"
            id="lead-notes"
            rows="4"
            class="form-control @error('notes') is-invalid @enderror"
        >{{ old('notes', $lead->notes) }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('dashboard.leads.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
</div>
