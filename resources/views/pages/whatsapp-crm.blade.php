@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp CRM Software India to Manage Leads & Close Faster | WhatsAppLeadCRM')
@section('meta_description', 'WhatsApp CRM software India for lead capture, pipeline tracking, and follow-ups. A WhatsApp lead management system built for Indian sales teams. Start a free trial.')
@section('canonical_url', route('whatsapp-crm', absolute: true))

@section('content')
    {{-- INTRO (Educational) --}}
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center" data-aos="fade-up">
            <span class="description-title">{{ __('Products') }}</span>
            <h1 class="mb-2">What is WhatsApp CRM?</h1>
            <p class="mb-0">
                A <strong>WhatsApp CRM software India</strong> teams use to <strong>manage leads</strong>, track conversations, and run follow-ups with structure.
                Think of it as a <strong>WhatsApp lead management system</strong> that turns chats into a pipeline your team can actually work.
            </p>
        </div>
    </section>

    <section class="section pb-2">
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-box h-100">
                        <h5>{{ __('Manage leads') }}</h5>
                        <p class="mb-0">{{ __('Capture enquiries from forms or campaigns and keep the full context in one lead profile.') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box h-100">
                        <h5>{{ __('Track conversations') }}</h5>
                        <p class="mb-0">{{ __('See status, notes, and next action so nothing is lost when multiple people handle WhatsApp leads.') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box h-100">
                        <h5>{{ __('Close deals faster') }}</h5>
                        <p class="mb-0">{{ __('Assignment + reminders drive consistent follow-up—the biggest lever for conversion in India sales teams.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS (Step flow) --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('How WhatsApp CRM works (step-by-step)') }}</h2>
            <p class="mb-0">{{ __('A simple flow your team repeats every day—from enquiry to customer.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 1: Lead comes in</h5><p class="mb-0">{{ __('From your website form, landing page, campaigns, or inbound WhatsApp.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 2: Stored in CRM</h5><p class="mb-0">{{ __('A lead profile is created with source, notes, and current stage.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 3: Assigned + followed up</h5><p class="mb-0">{{ __('Owner is responsible; reminders ensure next steps happen on time.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 4: Convert + track outcome</h5><p class="mb-0">{{ __('Move through stages until closed—so you learn what’s working.') }}</p></div></div>
            </div>
        </div>
    </section>

    {{-- VISUAL (Lead pipeline screenshot) --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Visual: lead pipeline') }}</h2>
            <p class="mb-0">{{ __('A pipeline view helps teams prioritize next actions and managers coach consistently.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                <img
                    src="{{ asset('front/images/landify/features/features-1.webp') }}"
                    class="img-fluid object-fit-cover"
                    alt="Lead pipeline view screenshot"
                    loading="lazy"
                >
            </div>
        </div>
    </section>

    {{-- CORE FEATURES (explained) + Visual (after 2 sections) --}}
    <section class="section light-background">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-up">
                    <div class="section-title mb-0">
                        <h2 class="mb-3">{{ __('Core features (what each one does)') }}</h2>
                        <p class="mb-0">{{ __('Each feature supports a real day-to-day sales workflow—capture, assign, follow up, and close.') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-delay="80">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="landing-feature-card h-100">
                                <h5>{{ __('Lead capture') }}</h5>
                                <p class="mb-0">{{ __('Create a lead profile with source and intent so reps know what to do first.') }}</p>
                                <p class="small text-muted mb-0">{{ __('Example: “2BHK enquiry – Ahmedabad – budget range”.') }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="landing-feature-card h-100">
                                <h5>{{ __('Auto reply / quick replies') }}</h5>
                                <p class="mb-0">{{ __('Acknowledge enquiries and standardize first response so teams reply fast.') }}</p>
                                <p class="small text-muted mb-0">{{ __('Example: “Thanks—sharing options, can we call in 10 minutes?”') }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="landing-feature-card h-100">
                                <h5>{{ __('Follow-up reminders') }}</h5>
                                <p class="mb-0">{{ __('Next follow-up date/time keeps the pipeline moving and reduces missed leads.') }}</p>
                                <p class="small text-muted mb-0">{{ __('Example: callback tomorrow 11:30 AM.') }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="landing-feature-card h-100">
                                <h5>{{ __('Team assignment') }}</h5>
                                <p class="mb-0">{{ __('Assign an owner so accountability is clear from first reply to closure.') }}</p>
                                <p class="small text-muted mb-0">{{ __('Example: route insurance leads to the renewal desk.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- VISUAL (Dashboard screenshot) --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('What the dashboard looks like') }}</h2>
            <p class="mb-0">{{ __('A single view for lead status, owner, notes, and next step.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                <img
                    src="{{ asset('front/images/landify/sections-images/hero.png') }}"
                    class="img-fluid object-fit-cover"
                    alt="WhatsApp CRM pipeline dashboard preview"
                    loading="lazy"
                >
            </div>
        </div>
    </section>

    {{-- USE CASES --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>WhatsApp CRM Use Cases in India</h2>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-md-12"><div class="landing-feature-card h-100"><h5>{{ __('Real estate') }}</h5><p>{{ __('Track site visits, brokers, and booking status across Mumbai, Delhi, Ahmedabad, Surat, and more.') }}</p></div></div>
                        <div class="col-md-12"><div class="landing-feature-card h-100"><h5>{{ __('Agencies') }}</h5><p>{{ __('Handle multiple campaigns, route leads by source, and report outcomes clearly.') }}</p></div></div>
                        <div class="col-md-12"><div class="landing-feature-card h-100"><h5>{{ __('Small business') }}</h5><p>{{ __('Keep every enquiry organized without complex tools—owners, stages, and reminders.') }}</p></div></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                        <img
                            src="{{ asset('front/images/landify/services/services-2.webp') }}"
                            class="img-fluid object-fit-cover"
                            alt="Chat and lead context illustration"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BENEFITS (Real outcomes) --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Benefits (real outcomes)') }}</h2>
            <p class="mb-0">{{ __('Practical improvements teams report when they move from chats/spreadsheets to a CRM workflow.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Faster response') }}</h5><p class="mb-0">{{ __('Leads don’t sit unread—ownership + quick replies reduce delay.') }}</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('No missed follow-ups') }}</h5><p class="mb-0">{{ __('Reminders make next steps explicit so leads don’t go cold.') }}</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Better tracking') }}</h5><p class="mb-0">{{ __('Managers see stage-wise progress and can coach the team based on real data.') }}</p></div></div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">{{ __('Start Free Trial') }}</h2>
            <p class="opacity-75 mb-4">{{ __('See how your team can move from chats to a measurable pipeline in a week.') }}</p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('register') }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">{{ __('Start Free Trial') }}</a>
                <a href="{{ route('pricing') }}" class="btn btn-label-secondary btn-lg px-5">{{ __('View pricing') }}</a>
            </div>
        </div>
    </section>
@endsection
