@extends('layouts.landing')

@section('body_class', 'inner-page')

@section('meta_title', 'Refund Policy | WhatsAppLeadCRM')
@section('meta_description', 'Refund Policy for WhatsAppLeadCRM. Learn how refunds are handled for subscription and wallet transactions when applicable.')
@section('canonical_url', route('refund-policy', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">{{ __('Refund Policy') }}</h1>
            <p class="mb-0">{{ __('We keep refunds simple and transparent. This policy applies when payments are enabled.') }}</p>
        </div>
    </section>

    <section class="section pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5 text-muted">
                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('Subscription payments') }}</h2>
                            <p class="mb-4">
                                {{ __('If a payment is deducted but the subscription does not activate due to a verified gateway error, contact support with the transaction reference and we will resolve it promptly.') }}
                            </p>

                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('API wallet top-ups') }}</h2>
                            <p class="mb-4">
                                {{ __('Wallet credits are applied only after backend verification. If a top-up is marked failed, no credits are added. If you see a mismatch, contact support with the Razorpay payment reference.') }}
                            </p>

                            <h2 class="h6 fw-bold text-dark mb-2">{{ __('How to request help') }}</h2>
                            <p class="mb-0">
                                {{ __('Email us or use the contact form with your account email and the payment reference. We may ask for additional details for verification.') }}
                            </p>
                        </div>
                    </div>
                    <p class="text-center small text-muted mt-3 mb-0">
                        <a href="{{ route('contact') }}">{{ __('Contact us') }}</a> {{ __('for refund-related questions.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

