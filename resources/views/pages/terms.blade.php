@extends('layouts.landing')

@section('body_class', 'inner-page')

@section('meta_title', 'Terms of Service | WhatsAppLeadCRM')
@section('meta_description', 'Terms of Service for WhatsAppLeadCRM. Understand acceptable use, account responsibilities, and service limits.')
@section('canonical_url', route('terms', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">{{ __('Terms of Service') }}</h1>
            <p class="mb-0">{{ __('These terms describe how the service can be used and what to expect.') }}</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5 text-muted">
                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('Acceptable use') }}</h2>
                            <ul class="mb-4">
                                <li>{{ __('Do not use the platform for spam, fraud, or unlawful messaging.') }}</li>
                                <li>{{ __('Follow WhatsApp and telecom rules applicable to your region.') }}</li>
                            </ul>

                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('Account responsibility') }}</h2>
                            <ul class="mb-4">
                                <li>{{ __('You are responsible for maintaining your login credentials and API keys.') }}</li>
                                <li>{{ __('You are responsible for the content you send and store through the platform.') }}</li>
                            </ul>

                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('Service availability') }}</h2>
                            <ul class="mb-0">
                                <li>{{ __('We aim for high uptime, but maintenance and external dependencies can affect availability.') }}</li>
                                <li>{{ __('Features may evolve as we improve reliability and compliance.') }}</li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-center small text-muted mt-3 mb-0">
                        {{ __('Need help?') }} <a href="{{ route('contact') }}">{{ __('Contact support') }}</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

