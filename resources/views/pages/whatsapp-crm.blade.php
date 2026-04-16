@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp CRM Software India to Manage Leads & Close Faster | WhatsAppLeadCRM')
@section('meta_description', 'WhatsApp CRM software India for lead capture, pipeline tracking, and follow-ups. A WhatsApp lead management system built for Indian sales teams. Start a free trial.')
@section('canonical_url', route('whatsapp-crm', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">WhatsApp CRM Software India for Lead Management &amp; Follow-ups</h1>
            <p class="mb-0">Use a <strong>WhatsApp lead management system</strong> to capture enquiries, assign ownership, track a visual pipeline, and follow up on time — built for sales teams across India. Need messaging automation too? Try our <a href="{{ route('whatsapp-api') }}">WhatsApp API provider India</a>.</p>
        </div>
    </section>

    <section class="section pb-2">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4"><div class="feature-box h-100"><h5>Faster response &amp; follow-ups</h5><p>Automatic assignment and follow-up reminders reduce delays so high-intent leads don’t go cold.</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>Pipeline visibility for managers</h5><p>Track stage-wise progress and team performance with simple, real-time pipeline views.</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>Clean lead history in one place</h5><p>Notes, status, owner, and next action stay attached to the lead — no scattered spreadsheets.</p></div></div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container section-title text-center"><h2>WhatsApp CRM Use Cases in India (Real Estate, Insurance &amp; Agencies)</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Real Estate Sales Teams</h5><p>Track site visit follow-ups, broker conversations, and booking status across cities like Mumbai, Delhi, and Surat.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Insurance Advisors</h5><p>Manage quote callbacks, renewals, and policy conversations with a clear next follow-up date.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Agencies &amp; Small Business</h5><p>Route leads by source and stage, measure outcomes, and keep your team accountable without extra tools.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container section-title text-center"><h2>What teams typically improve</h2></div>
        <div class="container"><div class="row g-4 text-center">
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">Faster</div><div class="social-stat-label">Response time with reminders and ownership</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">Higher</div><div class="social-stat-label">Follow-up completion with clear next steps</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">Better</div><div class="social-stat-label">Pipeline clarity for coaching and forecasting</div></div></div>
        </div></div>
    </section>

    @include('partials.pricing-plans-grid', [
        'plans' => $pricingPlans,
        'ctaMode' => 'landing',
        'landingCta' => fn () => route('register'),
        'enableAos' => true,
    ])

    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">Start closing more leads with WhatsApp CRM</h2>
            <p class="opacity-75 mb-4">Need messaging automation? Explore our <a href="{{ route('whatsapp-api') }}" class="text-white text-decoration-underline">WhatsApp API provider India</a> (also supports <strong>SMS API India</strong>). Want inbound enquiries too? Add <a href="{{ route('lead-generation') }}" class="text-white text-decoration-underline">lead generation services India</a> for high-intent demand.</p>
            <a href="{{ route('register') }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Start Free Trial</a>
        </div>
    </section>
@endsection
