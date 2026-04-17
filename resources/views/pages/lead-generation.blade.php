@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'Lead Generation Services India for Real Estate, Insurance & Agencies | WhatsAppLeadCRM')
@section('meta_description', 'Lead generation services India for high-intent enquiries. Buy leads in India with structured details, city targeting, and transparent qualification. Deliver leads to WhatsApp and manage them in CRM.')
@section('canonical_url', route('lead-generation', absolute: true))

@section('content')
    {{-- INTRO (Educational) --}}
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center" data-aos="fade-up">
            <span class="description-title">{{ __('Solutions') }}</span>
            <h1 class="mb-2">How we generate leads for you</h1>
            <p class="mb-0">
                Our <strong>lead generation services india</strong> are built around one goal: deliver structured, usable enquiries to your WhatsApp with clear context—so your team can follow up fast and track outcomes in <a href="{{ route('whatsapp-crm') }}">WhatsApp CRM software India</a>.
                If you want to <strong>buy leads india</strong> without spam, structure + intent are the difference.
            </p>
        </div>
    </section>

    {{-- STEP-BY-STEP SYSTEM --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Step-by-step system') }}</h2>
            <p class="mb-0">{{ __('A simple pipeline from landing page to WhatsApp delivery.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 1: Create landing page</h5><p class="mb-0">{{ __('We publish a niche + city page designed to capture intent with structured fields.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 2: Run ads</h5><p class="mb-0">{{ __('Traffic is driven from search/social campaigns aligned to your service.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 3: Capture leads</h5><p class="mb-0">{{ __('Forms collect name, city, need, and basic intent so teams can qualify faster.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 4: Send to WhatsApp</h5><p class="mb-0">{{ __('Leads are delivered to your WhatsApp for quick action—then tracked in CRM.') }}</p></div></div>
            </div>
        </div>
    </section>

    {{-- CORE FEATURES (with explanation) + Visual (Lead form UI) --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Core features (what you get)') }}</h2>
            <p class="mb-0">{{ __('Structure and delivery are the product—not vanity metrics.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-12"><div class="landing-feature-card h-100"><h5>{{ __('Structured lead capture') }}</h5><p class="mb-0">{{ __('Consistent fields (city/need/budget/timeframe) so reps can qualify quickly.') }}</p></div></div>
                        <div class="col-12"><div class="landing-feature-card h-100"><h5>{{ __('Deduplication') }}</h5><p class="mb-0">{{ __('Rules to reduce repeated entries and wasted follow-up time.') }}</p></div></div>
                        <div class="col-12"><div class="landing-feature-card h-100"><h5>{{ __('WhatsApp delivery') }}</h5><p class="mb-0">{{ __('Leads are shared to WhatsApp so teams can respond immediately.') }}</p></div></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                        <img
                            src="{{ asset('front/images/landify/sections-images/registration.png') }}"
                            class="img-fluid object-fit-cover"
                            alt="Lead form UI preview"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- VISUAL (Landing preview) --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Visual: landing page preview') }}</h2>
            <p class="mb-0">{{ __('This is where intent is captured and structured before it reaches your sales team.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                <img
                    src="{{ asset('front/images/landify/about/about-8.webp') }}"
                    class="img-fluid object-fit-cover"
                    alt="Landing page preview for lead generation in India"
                    loading="lazy"
                >
            </div>
        </div>
    </section>

    {{-- EXAMPLE (Sample lead data) --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Example: what a lead looks like') }}</h2>
            <p class="mb-0">{{ __('A simple, structured lead record your team can act on immediately.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="code-block-wrapper bg-dark p-4 p-md-5 rounded-4 shadow-lg position-relative" style="background-color: #0f172a !important; border: 1px solid rgba(255,255,255,0.1);">
                <pre class="text-info m-0" style="font-family: 'Fira Code', 'Courier New', monospace; font-size: 1rem; line-height: 1.6; overflow:auto;">
Name: Rahul
City: Ahmedabad
Interest: 2BHK
Budget: {{ money_local(4000000, 0) }}–{{ money_local(5500000, 0) }}
Preferred time: Evening
Notes: Wants options near metro, ready to visit this weekend
                </pre>
            </div>
        </div>
    </section>

    {{-- USE CASES (Industries) + Visual (WhatsApp receive screen) --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Use cases') }}</h2>
            <p class="mb-0">{{ __('Common categories teams run in India: real estate, insurance, and loans.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-12"><div class="feature-box h-100"><h5>{{ __('Real estate leads') }}</h5><p class="mb-0">{{ __('City + budget context so teams can prioritize quickly.') }}</p></div></div>
                        <div class="col-md-12"><div class="feature-box h-100"><h5>{{ __('Insurance enquiries') }}</h5><p class="mb-0">{{ __('Health/motor/life enquiries with callback-ready details.') }}</p></div></div>
                        <div class="col-md-12"><div class="feature-box h-100"><h5>{{ __('Loan enquiries') }}</h5><p class="mb-0">{{ __('Intent prompts and basic qualification so teams can act confidently.') }}</p></div></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                        <img
                            src="{{ asset('front/images/landify/sections-images/login.png') }}"
                            class="img-fluid object-fit-cover"
                            alt="WhatsApp lead delivery preview screen"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BENEFITS --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Benefits (real outcomes)') }}</h2>
            <p class="mb-0">{{ __('This works when teams respond fast and follow a clear process.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Faster first response') }}</h5><p class="mb-0">{{ __('Leads arrive with context so reps can reply immediately.') }}</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Cleaner qualification') }}</h5><p class="mb-0">{{ __('Structured fields reduce back-and-forth and wasted calls.') }}</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Better tracking') }}</h5><p class="mb-0">{{ __('Pair with CRM to track stages and improve conversion over time.') }}</p></div></div>
            </div>
        </div>
    </section>

    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">Need ready leads delivered on WhatsApp?</h2>
            <p class="opacity-75 mb-4">Want to manage and convert those leads? Pair this with <a href="{{ route('whatsapp-crm') }}" class="text-white text-decoration-underline">WhatsApp CRM software India</a> for follow-ups and pipeline tracking.</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('contact') }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Get Leads on WhatsApp</a>
                <a href="{{ route('whatsapp-crm') }}" class="btn btn-label-secondary btn-lg px-5">{{ __('See WhatsApp CRM') }}</a>
            </div>
        </div>
    </section>
@endsection
