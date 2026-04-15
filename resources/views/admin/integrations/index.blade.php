@extends('layouts.admin')

@section('title', __('Integrations'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Integrations') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Admin') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Integrations') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <button type="submit" form="integrations-form" class="ti-btn ti-btn-primary-full font-medium !mb-0" id="integrations-save-btn">
                <span class="ti-spinner !w-[1rem] !h-[1rem] hidden me-2" role="status" aria-hidden="true" id="integrations-spinner"></span>
                <i class="ri-save-line me-1"></i>
                {{ __('Save settings') }}
            </button>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
            <div class="flex items-center">
                <i class="ri-checkbox-circle-line me-2 text-lg"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-danger/10 text-danger border border-danger/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
            <div class="flex items-center">
                <i class="ri-error-warning-line me-2 text-lg"></i>
                {{ __('Please fix the errors below.') }}
            </div>
            <button type="button" class="text-danger" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <form method="post" action="{{ route('admin.integrations.update') }}" id="integrations-form">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-x-6">
            <div class="xxl:col-span-12 col-span-12">
                {{-- Platform Billing --}}
                <div class="box shadow-none border border-defaultborder/10">
                    <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                        <div>
                            <h4 class="box-title font-semibold">{{ __('Platform Billing Control') }}</h4>
                            <p class="text-textmuted text-xs mt-1">{{ __('Turn payments ON for paid SaaS mode, or OFF for free access mode.') }}</p>
                        </div>
                        <div class="flex items-center">
                            <input type="hidden" name="platform_payment_enabled" value="0">
                            <div class="ti-form-switch">
                                <input type="checkbox" name="platform_payment_enabled" value="1" id="payment_enabled_global" @checked($platform['payment_enabled'] ?? true) class="ti-form-switch-input">
                                <label for="payment_enabled_global" class="ti-form-switch-label">{{ __('Enable Payments') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-x-6">
                    {{-- Google Ads --}}
                    <div class="xl:col-span-6 col-span-12">
                        <div class="box shadow-none border border-defaultborder/10">
                            <div class="box-header !border-b !border-defaultborder/10">
                                <h4 class="box-title font-semibold">{{ __('Google Ads Settings') }}</h4>
                            </div>
                            <div class="box-body">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="google_ads_id">{{ __('Google Ads ID') }}</label>
                                        <input id="google_ads_id" name="google_ads_id" type="text" class="ti-form-input @error('google_ads_id') !border-danger @enderror" value="{{ old('google_ads_id', $google_ads['id'] ?? '') }}" placeholder="AW-XXXXXXXXX">
                                        @error('google_ads_id')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="google_ads_conversion_label">{{ __('Conversion Label') }}</label>
                                        <input id="google_ads_conversion_label" name="google_ads_conversion_label" type="text" class="ti-form-input @error('google_ads_conversion_label') !border-danger @enderror" value="{{ old('google_ads_conversion_label', $google_ads['conversion_label'] ?? '') }}" placeholder="ABC123XYZ">
                                        @error('google_ads_conversion_label')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Razorpay (Internal) --}}
                    <div class="xl:col-span-6 col-span-12">
                        <div class="box shadow-none border border-defaultborder/10">
                            <div class="box-header !border-b !border-defaultborder/10">
                                <h4 class="box-title font-semibold">{{ __('Razorpay (Platform Payments)') }}</h4>
                                <p class="text-hidden text-[10px] text-textmuted mt-1">{{ __('Keys are stored encrypted.') }}</p>
                            </div>
                            <div class="box-body">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="razorpay_key">{{ __('Key ID') }}</label>
                                        <input id="razorpay_key" name="razorpay_key" type="text" class="ti-form-input @error('razorpay_key') !border-danger @enderror" value="{{ old('razorpay_key', $razorpay['key'] ?? '') }}" placeholder="rzp_live_XXXX">
                                        @error('razorpay_key')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="razorpay_secret">{{ __('Key Secret') }}</label>
                                        <input id="razorpay_secret" name="razorpay_secret" type="password" class="ti-form-input @error('razorpay_secret') !border-danger @enderror" value="{{ old('razorpay_secret', $razorpay['secret'] ?? '') }}" placeholder="••••••••">
                                        @error('razorpay_secret')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="xl:col-span-12 col-span-12">
                        <div class="box shadow-none border border-defaultborder/10">
                            <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                                <h4 class="box-title font-semibold">{{ __('WhatsApp Cloud API') }}</h4>
                                <div class="ti-form-switch">
                                    <input type="checkbox" name="whatsapp_enabled" value="1" id="whatsapp_enabled" @checked($whatsapp['enabled'] ?? false) class="ti-form-switch-input">
                                    <label for="whatsapp_enabled" class="ti-form-switch-label">{{ __('Service Enabled') }}</label>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 lg:col-span-8">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="whatsapp_api_token">{{ __('Access Token (Permanent)') }}</label>
                                        <textarea id="whatsapp_api_token" name="whatsapp_api_token" rows="3" class="ti-form-input @error('whatsapp_api_token') !border-danger @enderror" placeholder="EAA..."></textarea>
                                        <p class="text-textmuted text-[10px] mt-1">{{ __('Stored encrypted. Leave blank to keep existing.') }}</p>
                                        @error('whatsapp_api_token')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="whatsapp_phone_number_id">{{ __('Phone Number ID') }}</label>
                                        <input id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" type="text" class="ti-form-input @error('whatsapp_phone_number_id') !border-danger @enderror" value="{{ old('whatsapp_phone_number_id', $whatsapp['phone_number_id'] ?? '') }}">
                                        @error('whatsapp_phone_number_id')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror

                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase mt-3" for="whatsapp_webhook_verify_token">{{ __('Webhook Verify Token') }}</label>
                                        <input id="whatsapp_webhook_verify_token" name="whatsapp_webhook_verify_token" type="text" class="ti-form-input @error('whatsapp_webhook_verify_token') !border-danger @enderror" value="{{ old('whatsapp_webhook_verify_token', $whatsapp['webhook_verify_token'] ?? '') }}">
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="whatsapp_inbound_organization_id">{{ __('Capture Inbound Leads to Organization ID') }}</label>
                                        <input id="whatsapp_inbound_organization_id" name="whatsapp_inbound_organization_id" type="number" class="ti-form-input @error('whatsapp_inbound_organization_id') !border-danger @enderror" value="{{ old('whatsapp_inbound_organization_id', $whatsapp['inbound_organization_id'] ?? '') }}" placeholder="e.g. 1">
                                        <p class="text-textmuted text-[11px] mt-1">{{ __('If set, inbound WhatsApp messages will auto-create/update leads inside this organization.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SMTP --}}
                    <div class="xl:col-span-12 col-span-12">
                        <div class="box shadow-none border border-defaultborder/10">
                            <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                                <h4 class="box-title font-semibold">{{ __('SMTP (Transactional Email)') }}</h4>
                                <div class="ti-form-switch">
                                    <input type="checkbox" name="smtp_enabled" value="1" id="smtp_enabled" @checked($smtp['enabled'] ?? false) class="ti-form-switch-input">
                                    <label for="smtp_enabled" class="ti-form-switch-label">{{ __('Override Global Config') }}</label>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="smtp_host">{{ __('SMTP Host') }}</label>
                                        <input id="smtp_host" name="smtp_host" type="text" class="ti-form-input" value="{{ old('smtp_host', $smtp['host'] ?? '') }}" placeholder="smtp.mailtrap.io">
                                    </div>
                                    <div class="col-span-12 lg:col-span-2">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="smtp_port">{{ __('Port') }}</label>
                                        <input id="smtp_port" name="smtp_port" type="number" class="ti-form-input" value="{{ old('smtp_port', $smtp['port'] ?? '') }}" placeholder="587">
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="smtp_username">{{ __('Username') }}</label>
                                        <input id="smtp_username" name="smtp_username" type="text" class="ti-form-input" value="{{ old('smtp_username', $smtp['username'] ?? '') }}">
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <label class="form-label font-bold text-textmuted text-[11px] uppercase" for="smtp_password">{{ __('Password') }}</label>
                                        <input id="smtp_password" name="smtp_password" type="password" class="ti-form-input" value="{{ old('smtp_password', $smtp['password'] ?? '') }}" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mb-10">
                    <button type="submit" class="ti-btn ti-btn-primary-full font-medium shadow-sm !px-6">
                        <i class="ri-save-line me-1"></i>
                        {{ __('Save all changes') }}
                    </button>
                </div>
            </div>
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
                sp.classList.remove('hidden');
            });
        })();
    </script>
@endpush

