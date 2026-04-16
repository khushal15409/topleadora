@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp API Provider India + SMS API India for OTP & Bulk Messaging | WhatsAppLeadCRM')
@section('meta_description', 'Reliable WhatsApp API provider India and SMS API India for OTP, alerts, and bulk messaging. Track delivery, manage usage, and scale messaging. Get API access today.')
@section('canonical_url', route('whatsapp-api', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">WhatsApp API Provider India for OTP, Alerts &amp; Bulk Messaging</h1>
            <p class="mb-0">Build faster with a <strong>WhatsApp API provider India</strong> plus <strong>SMS API India</strong> endpoints for OTP, alerts, and bulk WhatsApp messaging workflows. If you also need lead tracking after replies, use our <a href="{{ route('whatsapp-crm') }}">WhatsApp CRM software India</a>.</p>
        </div>
    </section>

    <section class="section">
        <div class="container"><div class="row g-4">
            <div class="col-md-3"><div class="feature-box h-100"><h5>OTP Delivery APIs (WhatsApp + SMS)</h5><p>Low-latency OTP endpoints with delivery status and callback-friendly flows.</p></div></div>
            <div class="col-md-3"><div class="feature-box h-100"><h5>Bulk WhatsApp messaging</h5><p>Campaign-grade sending controls with delivery logs and basic analytics.</p></div></div>
            <div class="col-md-3"><div class="feature-box h-100"><h5>Alerts &amp; webhooks</h5><p>Payment, order, and service alerts with webhook events for automation.</p></div></div>
            <div class="col-md-3"><div class="feature-box h-100"><h5>Usage tracking</h5><p>Monitor usage from a single dashboard so teams can scale with confidence.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container section-title text-center"><h2>Common WhatsApp &amp; SMS API Use Cases in India</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Login &amp; verification OTP</h5><p>Secure onboarding and account recovery with WhatsApp + SMS fallback options.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Bulk updates &amp; promotions</h5><p>Send broadcast campaigns and updates to segmented audiences with delivery visibility.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Operational notifications</h5><p>Order, dispatch, payment, and reminder alerts — especially useful for India-first teams.</p></div></div>
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
            <h2 class="mb-3">Launch your WhatsApp + SMS messaging stack in days</h2>
            <p class="opacity-75 mb-4">Need lead tracking after message replies? Use our <a href="{{ route('whatsapp-crm') }}" class="text-white text-decoration-underline">WhatsApp CRM software India</a>. Want inbound demand too? Add <a href="{{ route('lead-generation') }}" class="text-white text-decoration-underline">lead generation services India</a> for high-intent enquiries.</p>
            <a href="{{ route('register', ['service' => 'api']) }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Get API Access</a>
        </div>
    </section>
@endsection
