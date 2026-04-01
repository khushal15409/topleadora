@extends('layouts.admin')

@section('title', __('Integrations'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Integrations') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Manage API credentials securely (stored encrypted in DB).') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <strong>{{ __('Please fix the errors and try again.') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="post" action="{{ route('admin.integrations.update') }}" id="integrations-form">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ __('Platform billing') }}</h5>
                    <small class="text-body-secondary">{{ __('Turn payments ON for paid SaaS mode, or OFF for free access mode.') }}</small>
                </div>
                <div class="form-check form-switch m-0">
                    {{-- Unchecked checkboxes don't submit any value, so send an explicit 0. --}}
                    <input type="hidden" name="platform_payment_enabled" value="0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="platform_payment_enabled"
                        value="1"
                        id="payment_enabled_global"
                        @checked($platform['payment_enabled'] ?? true)
                    >
                    <label class="form-check-label" for="payment_enabled_global">{{ __('Enable Payments') }}</label>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="mb-0">{{ __('Google Ads Settings') }}</h5>
                <small class="text-body-secondary">{{ __('Configure gtag.js and conversion tracking from database settings.') }}</small>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="google_ads_id">{{ __('Google Ads ID') }}</label>
                        <input
                            id="google_ads_id"
                            name="google_ads_id"
                            type="text"
                            class="form-control @error('google_ads_id') is-invalid @enderror"
                            value="{{ old('google_ads_id', $google_ads['id'] ?? '') }}"
                            placeholder="AW-XXXXXXXXX"
                        >
                        @error('google_ads_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="google_ads_conversion_label">{{ __('Conversion Label') }}</label>
                        <input
                            id="google_ads_conversion_label"
                            name="google_ads_conversion_label"
                            type="text"
                            class="form-control @error('google_ads_conversion_label') is-invalid @enderror"
                            value="{{ old('google_ads_conversion_label', $google_ads['conversion_label'] ?? '') }}"
                            placeholder="ABC123XYZ"
                        >
                        @error('google_ads_conversion_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="mb-0">{{ __('Razorpay') }}</h5>
                <small class="text-body-secondary">{{ __('Used for subscription payments (stored encrypted in DB).') }}</small>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="razorpay_key">{{ __('Razorpay Key') }}</label>
                        <input
                            id="razorpay_key"
                            name="razorpay_key"
                            type="text"
                            class="form-control @error('razorpay_key') is-invalid @enderror"
                            value="{{ old('razorpay_key', $razorpay['key'] ?? '') }}"
                            placeholder="rzp_live_XXXXXXXXXXXXXX"
                        >
                        @error('razorpay_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="razorpay_secret">{{ __('Razorpay Secret') }}</label>
                        <input
                            id="razorpay_secret"
                            name="razorpay_secret"
                            type="password"
                            class="form-control @error('razorpay_secret') is-invalid @enderror"
                            value="{{ old('razorpay_secret', $razorpay['secret'] ?? '') }}"
                            placeholder="••••••••••••••••"
                        >
                        @error('razorpay_secret')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ __('WhatsApp Cloud API') }}</h5>
                    <small class="text-body-secondary">{{ __('Token + phone number ID + webhook verify token.') }}</small>
                </div>
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1" id="whatsapp_enabled" @checked($whatsapp['enabled'] ?? false)>
                    <label class="form-check-label" for="whatsapp_enabled">{{ __('Enabled') }}</label>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="whatsapp_api_token">{{ __('API Token') }}</label>
                        <textarea id="whatsapp_api_token" name="whatsapp_api_token" rows="3" class="form-control @error('whatsapp_api_token') is-invalid @enderror">{{ old('whatsapp_api_token', $whatsapp['api_token'] ?? '') }}</textarea>
                        @error('whatsapp_api_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsapp_phone_number_id">{{ __('Phone Number ID') }}</label>
                        <input id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" type="text" class="form-control @error('whatsapp_phone_number_id') is-invalid @enderror" value="{{ old('whatsapp_phone_number_id', $whatsapp['phone_number_id'] ?? '') }}">
                        @error('whatsapp_phone_number_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsapp_webhook_verify_token">{{ __('Webhook Verify Token') }}</label>
                        <input id="whatsapp_webhook_verify_token" name="whatsapp_webhook_verify_token" type="text" class="form-control @error('whatsapp_webhook_verify_token') is-invalid @enderror" value="{{ old('whatsapp_webhook_verify_token', $whatsapp['webhook_verify_token'] ?? '') }}">
                        @error('whatsapp_webhook_verify_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="whatsapp_inbound_organization_id">{{ __('Inbound leads organization (optional)') }}</label>
                        <input
                            id="whatsapp_inbound_organization_id"
                            name="whatsapp_inbound_organization_id"
                            type="number"
                            class="form-control @error('whatsapp_inbound_organization_id') is-invalid @enderror"
                            value="{{ old('whatsapp_inbound_organization_id', $whatsapp['inbound_organization_id'] ?? '') }}"
                            placeholder="e.g. 1"
                        >
                        <small class="text-body-secondary">{{ __('If set, inbound WhatsApp messages will auto-create/update leads inside this organization.') }}</small>
                        @error('whatsapp_inbound_organization_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ __('Payment gateway') }}</h5>
                    <small class="text-body-secondary">{{ __('Store Razorpay/Stripe keys here.') }}</small>
                </div>
                <div class="form-check form-switch m-0">
                    <input type="hidden" name="gateway_payment_enabled" value="0">
                    <input class="form-check-input" type="checkbox" name="gateway_payment_enabled" value="1" id="gateway_payment_enabled" @checked($payment['enabled'] ?? false)>
                    <label class="form-check-label" for="gateway_payment_enabled">{{ __('Enabled') }}</label>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="payment_key">{{ __('Key') }}</label>
                        <input id="payment_key" name="payment_key" type="text" class="form-control @error('payment_key') is-invalid @enderror" value="{{ old('payment_key', $payment['key'] ?? '') }}">
                        @error('payment_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="payment_secret">{{ __('Secret') }}</label>
                        <input id="payment_secret" name="payment_secret" type="password" class="form-control @error('payment_secret') is-invalid @enderror" value="{{ old('payment_secret', $payment['secret'] ?? '') }}">
                        @error('payment_secret')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ __('Email (SMTP)') }}</h5>
                    <small class="text-body-secondary">{{ __('Optional SMTP override for transactional email.') }}</small>
                </div>
                <div class="form-check form-switch m-0">
                    <input class="form-check-input" type="checkbox" name="smtp_enabled" value="1" id="smtp_enabled" @checked($smtp['enabled'] ?? false)>
                    <label class="form-check-label" for="smtp_enabled">{{ __('Enabled') }}</label>
                </div>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="smtp_host">{{ __('Host') }}</label>
                        <input id="smtp_host" name="smtp_host" type="text" class="form-control @error('smtp_host') is-invalid @enderror" value="{{ old('smtp_host', $smtp['host'] ?? '') }}">
                        @error('smtp_host')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="smtp_port">{{ __('Port') }}</label>
                        <input id="smtp_port" name="smtp_port" type="number" class="form-control @error('smtp_port') is-invalid @enderror" value="{{ old('smtp_port', $smtp['port'] ?? '') }}">
                        @error('smtp_port')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="smtp_username">{{ __('Username') }}</label>
                        <input id="smtp_username" name="smtp_username" type="text" class="form-control @error('smtp_username') is-invalid @enderror" value="{{ old('smtp_username', $smtp['username'] ?? '') }}">
                        @error('smtp_username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="smtp_password">{{ __('Password') }}</label>
                        <input id="smtp_password" name="smtp_password" type="password" class="form-control @error('smtp_password') is-invalid @enderror" value="{{ old('smtp_password', $smtp['password'] ?? '') }}">
                        @error('smtp_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" id="integrations-save-btn">
                <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true" id="integrations-spinner"></span>
                {{ __('Save settings') }}
            </button>
        </div>
    </form>
@endsection

@push('page-js')
    <script>
        (function () {
            const form = document.getElementById('integrations-form');
            const btn = document.getElementById('integrations-save-btn');
            const sp = document.getElementById('integrations-spinner');
            if (!form || !btn || !sp) return;
            form.addEventListener('submit', function () {
                btn.setAttribute('disabled', 'disabled');
                sp.classList.remove('d-none');
            });
        })();
    </script>
@endpush

