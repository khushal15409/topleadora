@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp API Provider India & SMS API India')
@section('meta_description', 'Use a reliable WhatsApp API provider India and SMS API India for OTP, alerts, and bulk messaging. Developer-ready setup.')
@section('canonical_url', route('whatsapp-api', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">WhatsApp API Provider India for OTP and Bulk Messaging</h1>
            <p class="mb-0">Build faster with <strong>bulk WhatsApp messaging API</strong> and <strong>SMS API India</strong> endpoints for authentication, alerts, and campaign automation.</p>
        </div>
    </section>

    <section class="section">
        <div class="container"><div class="row g-4">
            <div class="col-md-3"><div class="feature-box h-100"><h5>OTP Delivery APIs</h5><p>Low-latency OTP endpoints with status callbacks.</p></div></div>
            <div class="col-md-3"><div class="feature-box h-100"><h5>Bulk Messaging</h5><p>Campaign-grade sending controls with delivery logs.</p></div></div>
            <div class="col-md-3"><div class="feature-box h-100"><h5>Alerts & Webhooks</h5><p>Payment, order, and service alert workflows.</p></div></div>
            <div class="col-md-3"><div class="feature-box h-100"><h5>Usage & Wallet</h5><p>Track spend and message usage from a single dashboard.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container section-title text-center"><h2>Common API Use Cases for Indian Businesses</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>User Login OTP</h5><p>Secure onboarding and account recovery flows.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Bulk Promotions</h5><p>Schedule and send campaign messages by audience segments.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Operational Alerts</h5><p>Order, dispatch, payment, and reminder notifications.</p></div></div>
        </div></div>
    </section>

    @include('partials.pricing-plans-grid', [
        'plans' => $pricingPlans,
        'ctaMode' => 'landing',
        'landingCta' => fn () => route('register', ['service' => 'api']),
        'enableAos' => true,
    ])

    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">Launch your messaging stack in days, not months</h2>
            <p class="opacity-75 mb-4">Need lead tracking after message replies? Use our <a href="{{ route('whatsapp-crm') }}" class="text-white text-decoration-underline">WhatsApp CRM software India</a>. Need inbound demand? Check <a href="{{ route('lead-generation') }}" class="text-white text-decoration-underline">lead generation services India</a>.</p>
            <a href="{{ route('register', ['service' => 'api']) }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Get API Access</a>
        </div>
    </section>
@endsection
