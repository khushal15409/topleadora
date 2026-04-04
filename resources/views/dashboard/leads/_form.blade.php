@php
    $canAssign = $assignableUsers->isNotEmpty();
    $nicheOptions = $nicheOptions ?? [];
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
        <label class="form-label" for="lead-email">{{ __('Email') }}</label>
        <input
            type="email"
            name="email"
            id="lead-email"
            value="{{ old('email', $lead->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            autocomplete="email"
        >
        @error('email')
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
        <label class="form-label" for="lead-city">{{ __('City') }}</label>
        <input
            type="text"
            name="city"
            id="lead-city"
            value="{{ old('city', $lead->city) }}"
            class="form-control @error('city') is-invalid @enderror"
            autocomplete="address-level2"
        >
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label" for="lead-country">{{ __('Country') }}</label>
        <input
            type="text"
            name="country"
            id="lead-country"
            value="{{ old('country', $lead->country) }}"
            class="form-control @error('country') is-invalid @enderror"
            autocomplete="country-name"
        >
        @error('country')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    @if (count($nicheOptions) > 0)
        <div class="col-md-6">
            <label class="form-label" for="lead-niche">{{ __('Interest / niche') }}</label>
            <select name="niche" id="lead-niche" class="form-select @error('niche') is-invalid @enderror">
                <option value="">{{ __('— None —') }}</option>
                @foreach ($nicheOptions as $slug => $label)
                    <option value="{{ $slug }}" @selected(old('niche', $lead->niche) === $slug)>{{ $label }}</option>
                @endforeach
            </select>
            @error('niche')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label" for="lead-source-page">{{ __('Source page slug') }}</label>
            <input
                type="text"
                name="source_page"
                id="lead-source-page"
                value="{{ old('source_page', $lead->source_page) }}"
                class="form-control @error('source_page') is-invalid @enderror"
                placeholder="{{ __('e.g. loan') }}"
            >
            @error('source_page')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    @endif
    <div class="col-md-6">
        <label class="form-label" for="lead-campaign">{{ __('Campaign / UTM') }}</label>
        <input
            type="text"
            name="campaign"
            id="lead-campaign"
            value="{{ old('campaign', $lead->campaign) }}"
            class="form-control @error('campaign') is-invalid @enderror"
            placeholder="{{ __('e.g. spring_promo') }}"
        >
        @error('campaign')
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
        <label class="form-label" for="lead-message">{{ __('Message (landing / customer)') }}</label>
        <textarea
            name="message"
            id="lead-message"
            rows="3"
            class="form-control @error('message') is-invalid @enderror"
            placeholder="{{ __('Optional message from lead form') }}"
        >{{ old('message', $lead->message) }}</textarea>
        @error('message')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
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
