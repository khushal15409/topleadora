@extends('layouts.onboarding')

@section('title', 'Connect WhatsApp')

@section('content')
    <div class="saas-modal-backdrop saas-modal-backdrop--visible" aria-hidden="true"></div>
    <div class="saas-onboarding-shell d-flex align-items-center justify-content-center min-vh-100 px-3 py-5">
        <div class="card saas-onboarding-card shadow-lg border-0 w-100" style="max-width: 28rem;">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <img
                        src="{{ asset('front/images/logo.png') }}"
                        alt="{{ config('app.name') }}"
                        class="mb-3"
                        style="height: 40px; width: auto;"
                    >
                    <h4 class="mb-1">Connect Your WhatsApp Number</h4>
                    <p class="text-body-secondary small mb-0">
                        Add your business mobile number and confirm your organization name to continue to the dashboard.
                    </p>
                </div>

                <form method="post" action="{{ route('admin.onboarding.store') }}" class="d-flex flex-column gap-3" novalidate>
                    @csrf

                    <div>
                        <label class="form-label" for="onboarding-mobile">Mobile number <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="mobile_number"
                            id="onboarding-mobile"
                            class="form-control @error('mobile_number') is-invalid @enderror"
                            value="{{ old('mobile_number', $organization->mobile_number) }}"
                            placeholder="+91 98765 43210"
                            required
                            autocomplete="tel"
                            autofocus
                        >
                        @error('mobile_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="onboarding-org-name">Organization name <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="organization_name"
                            id="onboarding-org-name"
                            class="form-control @error('organization_name') is-invalid @enderror"
                            value="{{ old('organization_name', $organization->name) }}"
                            required
                            autocomplete="organization"
                        >
                        @error('organization_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Save &amp; Continue
                    </button>
                </form>

                <form method="post" action="{{ route('logout') }}" class="mt-4 text-center mb-0">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-body-secondary text-decoration-none p-0">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
