@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp CRM Software India | WhatsAppLeadCRM')
@section('meta_description', 'WhatsApp CRM software India for real estate, insurance, and agencies. Capture leads, automate follow-ups, and close faster.')
@section('canonical_url', route('whatsapp-crm', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">WhatsApp CRM Software India for Lead Management</h1>
            <p class="mb-0">This <strong>WhatsApp lead management system</strong> helps Indian sales teams capture every enquiry, automate follow-ups, and increase close rates.</p>
        </div>
    </section>

    <section class="section pb-2">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4"><div class="feature-box h-100"><h5>Respond 35% faster</h5><p>Automatic assignment and follow-up reminders reduce response delay and leakage.</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>Pipeline clarity for managers</h5><p>Track stage-wise conversion in real time and coach reps with actual data.</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>Higher lead-to-deal conversion</h5><p>Keep context, notes, and ownership in one view so no lead goes cold.</p></div></div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container section-title text-center"><h2>Use Cases in Real Estate, Insurance, and Agencies</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Real Estate Teams</h5><p>Track site visit follow-ups, broker communication, and booking status.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Insurance Advisors</h5><p>Manage quote callbacks and policy lifecycle conversations in one place.</p></div></div>
            <div class="col-md-4"><div class="landing-feature-card h-100"><h5>Sales Agencies</h5><p>Segment leads by source, route to reps, and improve closure accountability.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container section-title text-center"><h2>Results</h2></div>
        <div class="container"><div class="row g-4 text-center">
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">35%</div><div class="social-stat-label">Faster response time</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">22%</div><div class="social-stat-label">Higher follow-up completion</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">18%</div><div class="social-stat-label">Avg. conversion lift</div></div></div>
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
            <h2 class="mb-3">Start closing more leads on WhatsApp</h2>
            <p class="opacity-75 mb-4">Also need messaging automation? Explore our <a href="{{ route('whatsapp-api') }}" class="text-white text-decoration-underline">WhatsApp API provider India</a>. Need inbound demand? See <a href="{{ route('lead-generation') }}" class="text-white text-decoration-underline">lead generation services India</a>.</p>
            <a href="{{ route('register') }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Start Free Trial</a>
        </div>
    </section>
@endsection
