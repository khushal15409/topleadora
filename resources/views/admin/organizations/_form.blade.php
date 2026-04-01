@php
    /** @var \App\Models\Organization|null $organization */
    $organization = $organization ?? null;
    $isEdit = $organization !== null;
@endphp

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label" for="org-name">Organization name <span class="text-danger">*</span></label>
        <input
            type="text"
            name="name"
            id="org-name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', optional($organization)->name) }}"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="org-slug">Slug</label>
        <input
            type="text"
            name="slug"
            id="org-slug"
            class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', optional($organization)->slug) }}"
            placeholder="Auto from name if empty"
        >
        @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="org-status">Status <span class="text-danger">*</span></label>
        <select name="status" id="org-status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($organization)->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="org-plan">Plan</label>
        <select name="plan_id" id="org-plan" class="form-select @error('plan_id') is-invalid @enderror">
            <option value="">— No plan (trial) —</option>
            @foreach ($plans as $plan)
                <option
                    value="{{ $plan->id }}"
                    @selected((string) old('plan_id', optional($organization)->plan_id) === (string) $plan->id)
                >
                    {{ $plan->name }} ({{ $plan->currency }} {{ number_format((float) $plan->price_monthly, 0) }}/mo)
                </option>
            @endforeach
        </select>
        @error('plan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Assigning a plan clears trial dates and grants paid access.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="org-trial-ends">Trial ends at</label>
        <input
            type="datetime-local"
            name="trial_ends_at"
            id="org-trial-ends"
            class="form-control @error('trial_ends_at') is-invalid @enderror"
            value="{{ old('trial_ends_at', optional($organization)->trial_ends_at?->format('Y-m-d\TH:i')) }}"
        >
        @error('trial_ends_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label" for="org-mobile">WhatsApp / mobile</label>
        <input
            type="text"
            name="mobile_number"
            id="org-mobile"
            class="form-control @error('mobile_number') is-invalid @enderror"
            value="{{ old('mobile_number', optional($organization)->mobile_number) }}"
        >
        @error('mobile_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="is_trial"
                id="org-is-trial"
                value="1"
                @checked(old('is_trial', optional($organization)->is_trial ?? true))
            >
            <label class="form-check-label" for="org-is-trial">Trial mode (when no plan)</label>
        </div>
        <div class="form-check mt-2">
            <input
                class="form-check-input"
                type="checkbox"
                name="onboarding_completed"
                id="org-onboarding"
                value="1"
                @checked(old('onboarding_completed', optional($organization)->onboarding_completed ?? false))
            >
            <label class="form-check-label" for="org-onboarding">Onboarding completed</label>
        </div>
    </div>
</div>
