@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'Lead Generation Services India for Real Estate, Insurance & Agencies | WhatsAppLeadCRM')
@section('meta_description', 'Lead generation services India for high-intent enquiries. Buy leads in India with structured details, city targeting, and transparent qualification. Deliver leads to WhatsApp and manage them in CRM.')
@section('canonical_url', route('lead-generation', absolute: true))

@section('content')
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center">
            <h1 class="mb-2">Lead Generation Services India for High-Intent Enquiries</h1>
            <p class="mb-0">Need to <strong>buy leads in India</strong>? Get structured real estate leads (city-based), insurance enquiries, and agency demand delivered to your team on WhatsApp — and manage follow-ups in <a href="{{ route('whatsapp-crm') }}">WhatsApp CRM software India</a>.</p>
        </div>
    </section>

    <section class="section">
        <div class="container section-title text-center"><h2>Industry-Specific Lead Generation Across India</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="feature-box h-100"><h5>Real Estate Leads (City-based)</h5><p>Buyer and investor enquiries segmented by city and budget intent — ideal for follow-up teams.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>Insurance Enquiries</h5><p>Health, motor, and life policy enquiries with callback details and clear intent signals.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>Agency Demand</h5><p>Service leads aligned to package interest so your team can qualify fast and close quicker.</p></div></div>
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
            <div class="col-md-4"><div class="feature-box h-100"><h5>1. Choose niche + city</h5><p>Select your target audience and city focus (e.g., Mumbai, Delhi, Surat) based on your capacity.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>2. Capture intent with forms</h5><p>Landing pages capture enquiry details with qualification prompts and cleaner lead data.</p></div></div>
            <div class="col-md-4"><div class="feature-box h-100"><h5>3. Leads delivered to WhatsApp</h5><p>Receive structured lead details quickly for immediate follow-up — then track outcomes in CRM.</p></div></div>
        </div></div>
    </section>

    <section class="section light-background">
        <div class="container section-title text-center"><h2>Lead Quality Standards</h2></div>
        <div class="container"><div class="row g-4">
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">Structured</div><div class="social-stat-label">Consistent fields for faster qualification</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">Faster</div><div class="social-stat-label">Delivery timelines aligned to campaign setup</div></div></div>
            <div class="col-md-4"><div class="social-stat-item"><div class="social-stat-value">Actionable</div><div class="social-stat-label">Best-practice follow-up guidance for teams</div></div></div>
        </div></div>
    </section>

    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">Need ready leads delivered on WhatsApp?</h2>
            <p class="opacity-75 mb-4">Need to manage and convert those leads? Pair this with <a href="{{ route('whatsapp-crm') }}" class="text-white text-decoration-underline">WhatsApp CRM software India</a> and message automation from a <a href="{{ route('whatsapp-api') }}" class="text-white text-decoration-underline">WhatsApp API provider India</a>.</p>
            <a href="{{ route('contact') }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Get Leads on WhatsApp</a>
        </div>
    </section>
@endsection
