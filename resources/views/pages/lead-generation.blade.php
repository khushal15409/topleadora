@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'Lead Generation Services India | WhatsApp Leads')
@section('meta_description', 'Buy leads in India for real estate, insurance, and agencies. Get structured lead details on WhatsApp with transparent qualification.')
@section('canonical_url', route('lead-generation', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">Lead Generation Services India for High-Intent Enquiries</h1>
            <p class="mb-0">Need to <strong>buy leads in India</strong>? Get structured real estate leads India, insurance enquiries, and agency demand delivered to your team on WhatsApp.</p>
        </div>
    </section>

    <section class="section">
        <div class="container section-title text-center"><h2>Industry-Specific Lead Targeting Across India</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="feature-box h-100"><h5>Real Estate</h5><p>Buyer and investor enquiries segmented by city and budget intent.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>Insurance</h5><p>Health, motor, and life policy enquiries with callback details.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>Agencies</h5><p>Performance marketing and service leads aligned to package interest.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container"><div class="row g-4">
            <div class="col-md-6"><div class="landing-feature-card h-100"><h5>Who this is for</h5><p>Teams with a follow-up process, dedicated sales reps, and clear service-market fit.</p></div></div>
            <div class="col-md-6"><div class="landing-feature-card h-100"><h5>Who this is not for</h5><p>Businesses expecting instant sales without call handling, qualification, or sales accountability.</p></div></div>
        </div></div>
    </section>

    <section class="section">
        <div class="container section-title text-center"><h2>How it works</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="feature-box h-100"><h5>1. Choose niche + city</h5><p>Select your target audience and campaign scope.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>2. We run intent pages</h5><p>Landing pages capture enquiry details with qualification prompts.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>3. Leads sent to WhatsApp</h5><p>Receive structured lead details quickly for immediate follow-up.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container section-title text-center"><h2>Lead Quality Standards</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">100%</div><div class="social-stat-label">Phone verified format</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">24h</div><div class="social-stat-label">Delivery window target</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">3x</div><div class="social-stat-label">Retry follow-up recommendation</div></div></div>
        </div></div>
    </section>

    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">Need ready leads delivered on WhatsApp?</h2>
            <p class="opacity-75 mb-4">Need to manage and convert those leads? Pair this with <a href="{{ route('whatsapp-crm') }}" class="text-white text-decoration-underline">WhatsApp CRM software India</a> and <a href="{{ route('whatsapp-api') }}" class="text-white text-decoration-underline">WhatsApp API provider India</a>.</p>
            <a href="{{ route('contact') }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Get Leads on WhatsApp</a>
        </div>
    </section>
@endsection
