@php
    /** @var \App\Models\Organization|null $organization */
    $organization = $organization ?? null;
    $isEdit = $organization !== null;
@endphp

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-6">
        <label class="block text-sm font-medium mb-2" for="org-name">{{ __('Organization name') }} <span
                class="text-danger">*</span></label>
        <input type="text" name="name" id="org-name" class="ti-form-input @error('name') !border-danger @enderror"
            value="{{ old('name', optional($organization)->name) }}" required
            placeholder="{{ __('Enter organization name') }}">
        @error('name')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-12 lg:col-span-6">
        <label class="block text-sm font-medium mb-2" for="org-slug">{{ __('Slug') }}</label>
        <input type="text" name="slug" id="org-slug" class="ti-form-input @error('slug') !border-danger @enderror"
            value="{{ old('slug', optional($organization)->slug) }}"
            placeholder="{{ __('Auto-generated from name if empty') }}">
        @error('slug')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-12 lg:col-span-6">
        <label class="block text-sm font-medium mb-2" for="org-status">{{ __('Status') }} <span
                class="text-danger">*</span></label>
        <select name="status" id="org-status" class="ti-form-select @error('status') !border-danger @enderror" required>
            @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', optional($organization)->status ?? 'active') === $value)>
                    {{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-12 lg:col-span-6">
        <label class="block text-sm font-medium mb-2" for="org-plan">{{ __('Plan') }}</label>
        <select name="plan_id" id="org-plan" class="ti-form-select @error('plan_id') !border-danger @enderror">
            <option value="">— {{ __('No plan (trial)') }} —</option>
            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" @selected((string) old('plan_id', optional($organization)->plan_id) === (string) $plan->id)>
                    {{ $plan->name }} ({{ $plan->currency }} {{ number_format((float) $plan->price_monthly, 0) }}/mo)
                </option>
            @endforeach
        </select>
        @error('plan_id')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
        <p class="text-textmuted text-[11px] mt-1">
            {{ __('Assigning a plan clears trial dates and grants paid access.') }}</p>
    </div>

    <div class="col-span-12 lg:col-span-6">
        <label class="block text-sm font-medium mb-2" for="org-trial-ends">{{ __('Trial ends at') }}</label>
        <input type="datetime-local" name="trial_ends_at" id="org-trial-ends"
            class="ti-form-input @error('trial_ends_at') !border-danger @enderror"
            value="{{ old('trial_ends_at', optional($organization)->trial_ends_at?->format('Y-m-d\TH:i')) }}">
        @error('trial_ends_at')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-12 lg:col-span-6">
        <label class="block text-sm font-medium mb-2" for="org-mobile">{{ __('WhatsApp / mobile') }}</label>
        <input type="text" name="mobile_number" id="org-mobile"
            class="ti-form-input @error('mobile_number') !border-danger @enderror"
            value="{{ old('mobile_number', optional($organization)->mobile_number) }}"
            placeholder="{{ __('e.g. +1234567890') }}">
        @error('mobile_number')
            <p class="text-danger text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-12 space-y-4 pt-2">
        <div class="flex items-center">
            <input class="ti-form-checkbox rounded-sm" type="checkbox" name="is_trial" id="org-is-trial" value="1"
                @checked(old('is_trial', optional($organization)->is_trial ?? true))>
            <label class="ms-3 text-sm font-medium" for="org-is-trial">{{ __('Trial mode (when no plan)') }}</label>
        </div>
        <div class="flex items-center">
            <input class="ti-form-checkbox rounded-sm" type="checkbox" name="onboarding_completed" id="org-onboarding"
                value="1" @checked(old('onboarding_completed', optional($organization)->onboarding_completed ?? false))>
            <label class="ms-3 text-sm font-medium" for="org-onboarding">{{ __('Onboarding completed') }}</label>
        </div>
    </div>
</div>