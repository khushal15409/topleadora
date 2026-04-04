@props([
    'slug',
    'submitLabel' => __('Get free consultation'),
    'servicesForForm',
    'countriesForForm',
    'defaultServiceId' => null,
    'dynamicFormFields',
    'selectedServiceName' => null,
])

@php
    $indiaCountry = ($countriesForForm ?? collect())->firstWhere('code', 'IN');
    $utmCampaign = old('utm_campaign', request()->query('utm_campaign', request()->query('campaign')));
    $utmSource = old('utm_source', request()->query('utm_source'));
    $utmMedium = old('utm_medium', request()->query('utm_medium'));
    $servicesEmpty = $servicesForForm === null || $servicesForForm->isEmpty();
    $countriesEmpty = $countriesForForm === null || $countriesForForm->isEmpty() || $indiaCountry === null;
@endphp

<form
    id="lead-capture-form"
    class="leads-capture-form leads-capture-form--modern"
    action="{{ route('leads.capture.store') }}"
    method="post"
    novalidate
    data-page-slug="{{ $slug }}"
    data-default-service-id="{{ $defaultServiceId ?? '' }}"
>
    @csrf
    <input type="hidden" name="source_page" value="{{ $slug }}">
    <input type="hidden" name="utm_source" value="{{ $utmSource }}">
    <input type="hidden" name="utm_medium" value="{{ $utmMedium }}">
    <input type="hidden" name="utm_campaign" value="{{ $utmCampaign }}">
    <input type="hidden" name="service_id" id="lc-service-id" value="{{ $defaultServiceId ?? '' }}">
    @if (! $servicesEmpty && ! $countriesEmpty && $indiaCountry !== null)
        <input type="hidden" name="country_id" id="lc-country-id" value="{{ (int) $indiaCountry->id }}">
    @endif

    @if ($servicesEmpty || $countriesEmpty)
        <div class="alert alert-warning small mb-0">
            {{ __('Marketing form is not fully configured. Please contact the site administrator.') }}
        </div>
    @else
        <div class="leads-form-grid">
            <div class="leads-form-field">
                <label for="lc-name" class="form-label small fw-semibold text-muted mb-1">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="name"
                    id="lc-name"
                    class="form-control form-control-lg"
                    required
                    autocomplete="name"
                    maxlength="255"
                >
                <div class="invalid-feedback d-none small" data-error-for="name"></div>
            </div>
            <div class="leads-form-field">
                <label for="lc-phone" class="form-label small fw-semibold text-muted mb-1">{{ __('Mobile Number') }} <span class="text-danger">*</span></label>
                <input
                    type="tel"
                    name="phone"
                    id="lc-phone"
                    class="form-control form-control-lg"
                    required
                    autocomplete="tel"
                    inputmode="numeric"
                    pattern="[0-9]{10}"
                    maxlength="10"
                >
                <div class="invalid-feedback d-none small" data-error-for="phone"></div>
            </div>
            <div class="leads-form-field">
                <label for="lc-email" class="form-label small fw-semibold text-muted mb-1">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                <input
                    type="email"
                    name="email"
                    id="lc-email"
                    class="form-control form-control-lg"
                    required
                    autocomplete="email"
                    maxlength="255"
                >
                <div class="invalid-feedback d-none small" data-error-for="email"></div>
            </div>
            <div class="leads-form-field">
                <label for="lc-city" class="form-label small fw-semibold text-muted mb-1">{{ __('City') }} <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="city"
                    id="lc-city"
                    class="form-control form-control-lg"
                    required
                    autocomplete="address-level2"
                    maxlength="128"
                >
                <div class="invalid-feedback d-none small" data-error-for="city"></div>
            </div>
            <div class="leads-form-field">
                <label for="lc-country" class="form-label small fw-semibold text-muted mb-1">{{ __('Country') }} <span class="text-danger">*</span></label>
                <select id="lc-country" class="form-select bg-light" disabled aria-disabled="true" title="{{ __('Country is fixed for this campaign') }}">
                    <option selected>{{ $indiaCountry->name }} ({{ $indiaCountry->code }})</option>
                </select>
                <div class="invalid-feedback d-none small" data-error-for="country_id"></div>
            </div>
            <div class="leads-form-field">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Selected Service') }} <span class="text-danger">*</span></label>
                <div class="leads-selected-service rounded-3 border bg-light px-3 py-3">
                    <span id="lc-service-label" class="fw-semibold text-dark mb-0">{{ $selectedServiceName ?? '—' }}</span>
                </div>
                <div class="invalid-feedback d-none small" data-error-for="service_id"></div>
            </div>
            @foreach ($dynamicFormFields as $field)
                @php
                    $isAdditionalFullWidth = $field->field_key === 'message';
                @endphp
                <div @class(['leads-form-field', 'leads-form-field--full' => $isAdditionalFullWidth])>
                    @if ($field->field_type === 'textarea')
                        <label for="lc-extra-{{ $field->field_key }}" class="form-label small fw-semibold text-muted mb-1">{{ $field->label }}@if ($field->is_required) <span class="text-danger">*</span> @endif</label>
                        <textarea
                            name="extra[{{ $field->field_key }}]"
                            id="lc-extra-{{ $field->field_key }}"
                            class="form-control form-control-lg"
                            style="min-height: 6rem"
                            @if ($field->is_required) required @endif
                            maxlength="5000"
                        ></textarea>
                    @else
                        <label for="lc-extra-{{ $field->field_key }}" class="form-label small fw-semibold text-muted mb-1">{{ $field->label }}@if ($field->is_required) <span class="text-danger">*</span> @endif</label>
                        <input
                            type="{{ $field->field_type === 'email' ? 'email' : 'text' }}"
                            name="extra[{{ $field->field_key }}]"
                            id="lc-extra-{{ $field->field_key }}"
                            class="form-control form-control-lg"
                            @if ($field->is_required) required @endif
                            maxlength="2000"
                        >
                    @endif
                    <div class="invalid-feedback d-none small" data-error-for="extra.{{ $field->field_key }}"></div>
                </div>
            @endforeach
            <div class="leads-form-field leads-form-field--full leads-form-field--submit pt-1">
                <button type="submit" class="btn btn-leads-submit w-100 py-3" id="lead-capture-submit">
                    <span class="lead-submit-label">{{ $submitLabel }}</span>
                    <span class="lead-submit-loading d-none spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    @endif
</form>
