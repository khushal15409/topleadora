@extends('layouts.landing')

@section('body_class', 'inner-page')

@section('meta_title', 'Privacy Policy | WhatsAppLeadCRM')
@section('meta_description', 'Privacy Policy for WhatsAppLeadCRM. Learn what data we collect, how we use it, and your choices.')
@section('canonical_url', route('privacy-policy', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">{{ __('Privacy Policy') }}</h1>
            <p class="mb-0">{{ __('This page explains how we collect and use information when you use WhatsAppLeadCRM.') }}</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5 text-muted">
                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('What we collect') }}</h2>
                            <ul class="mb-4">
                                <li>{{ __('Account information you provide (name, email, organization details).') }}</li>
                                <li>{{ __('Usage data needed to operate the platform (feature usage, logs, and basic diagnostics).') }}</li>
                                <li>{{ __('Billing/payment metadata when payments are enabled (processed by third-party gateways).') }}</li>
                            </ul>

                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('How we use data') }}</h2>
                            <ul class="mb-4">
                                <li>{{ __('To provide and improve the service, including support and security.') }}</li>
                                <li>{{ __('To prevent abuse and keep accounts safe.') }}</li>
                                <li>{{ __('To communicate important product updates and service messages.') }}</li>
                            </ul>

                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('Your choices') }}</h2>
                            <ul class="mb-0">
                                <li>{{ __('You can request account deletion by contacting support.') }}</li>
                                <li>{{ __('You can opt out of non-essential emails.') }}</li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-center small text-muted mt-3 mb-0">
                        {{ __('Questions?') }} <a href="{{ route('contact') }}">{{ __('Contact us') }}</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

