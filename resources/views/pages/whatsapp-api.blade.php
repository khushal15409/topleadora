@extends('layouts.landing')

@section('body_class', 'inner-page')
@section('meta_title', 'WhatsApp API Provider India + SMS API India for OTP & Bulk Messaging | WhatsAppLeadCRM')
@section('meta_description', 'Reliable WhatsApp API provider India and SMS API India for OTP, alerts, and bulk messaging. Track delivery, manage usage, and scale messaging. Get API access today.')
@section('canonical_url', route('whatsapp-api', absolute: true))

@section('content')
    {{-- INTRO (Educational) --}}
    <section class="section light-background pt-5 mt-5">
        <div class="container section-title text-center" data-aos="fade-up">
            <span class="description-title">{{ __('Products') }}</span>
            <h1 class="mb-2">What can you do with WhatsApp &amp; SMS APIs?</h1>
            <p class="mb-0">
                Use a <strong>whatsapp api provider india</strong> and <strong>sms api india</strong> to send OTPs, bulk messages, and notifications from your product.
                This page explains the core use cases, the request flow, and a simple example payload.
            </p>
        </div>
    </section>

    {{-- HOW IT WORKS (Step-by-step) --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('How the API works (step-by-step)') }}</h2>
            <p class="mb-0">{{ __('User action → API call → delivery → logs/webhooks.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 1: Trigger event</h5><p class="mb-0">{{ __('Login/signup/checkout triggers OTP, alert, or campaign event.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 2: Call endpoint</h5><p class="mb-0">{{ __('Your backend sends payload to WhatsApp/SMS API endpoint.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 3: Delivery + status</h5><p class="mb-0">{{ __('Message is sent; status is tracked via logs/webhooks.') }}</p></div></div>
                <div class="col-md-3"><div class="feature-box h-100"><h5>Step 4: Retry/monitor</h5><p class="mb-0">{{ __('Handle failures safely with monitoring and retry behavior.') }}</p></div></div>
            </div>
        </div>
    </section>

    {{-- USE CASES --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>Use cases (what teams build with the API)</h2>
            <p class="mb-0">{{ __('OTP, bulk messaging, and notifications are the common “day one” workflows.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-4"><div class="landing-feature-card h-100"><h5>{{ __('OTP sending') }}</h5><p>{{ __('Login, verification, and account recovery via WhatsApp with SMS fallback.') }}</p></div></div>
                <div class="col-md-4"><div class="landing-feature-card h-100"><h5>{{ __('Bulk messaging') }}</h5><p>{{ __('Campaign updates and reminders using a bulk whatsapp messaging api workflow.') }}</p></div></div>
                <div class="col-md-4"><div class="landing-feature-card h-100"><h5>{{ __('Notifications') }}</h5><p>{{ __('Order, dispatch, payment, and service alerts with webhook-ready automation.') }}</p></div></div>
            </div>
        </div>
    </section>

    {{-- CORE FEATURES (OTP in seconds + code example) --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Send OTP in seconds') }}</h2>
            <p class="mb-0">{{ __('A simple request payload your backend can call. (Keyword: otp api india)') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row gy-4 align-items-center mb-3">
                <div class="col-lg-6">
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                        <img
                            src="{{ asset('front/images/landify/about/about-11.webp') }}"
                            class="img-fluid object-fit-cover"
                            alt="API dashboard visual preview"
                            loading="lazy"
                        >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="landing-feature-card h-100">
                        <h5 class="mb-2">{{ __('Where this fits') }}</h5>
                        <p class="mb-0">{{ __('Use this for login/signup verification, transaction alerts, and operational notifications.') }}</p>
                    </div>
                </div>
            </div>
            <div class="code-block-wrapper bg-dark p-4 p-md-5 rounded-4 shadow-lg position-relative" style="background-color: #0f172a !important; border: 1px solid rgba(255,255,255,0.1);">
                <pre class="text-info m-0" style="font-family: 'Fira Code', 'Courier New', monospace; font-size: 1rem; line-height: 1.6; overflow:auto;">
POST /send-otp
{
  "mobile": "9876543210",
  "message": "Your OTP is 1234"
}
                </pre>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <div class="landing-feature-card h-100">
                        <h5 class="mb-2">{{ __('What this does') }}</h5>
                        <p class="mb-0">{{ __('Your server calls the endpoint, the OTP is delivered (WhatsApp with SMS fallback), and you track delivery status.') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="landing-feature-card h-100">
                        <h5 class="mb-2">{{ __('Good practice') }}</h5>
                        <p class="mb-0">{{ __('Validate mobile numbers and keep OTP expiry short (e.g., 5 minutes).') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- VISUAL (after 2–3 sections) --}}
    <section class="section">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Visual: API flow diagram') }}</h2>
            <p class="mb-0">{{ __('User → API → delivery → logs/webhooks.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                <img
                    src="{{ asset('front/images/landify/misc/misc-6.webp') }}"
                    class="img-fluid object-fit-cover"
                    alt="API flow diagram preview"
                    loading="lazy"
                >
            </div>
        </div>
    </section>

    {{-- BENEFITS --}}
    <section class="section light-background">
        <div class="container section-title text-center" data-aos="fade-up">
            <h2>{{ __('Benefits (why teams use this API)') }}</h2>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row g-4">
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Fast delivery') }}</h5><p class="mb-0">{{ __('Designed for OTP and alerts where seconds matter.') }}</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Reliable') }}</h5><p class="mb-0">{{ __('Logs and verification patterns so you can debug and operate safely.') }}</p></div></div>
                <div class="col-md-4"><div class="feature-box h-100"><h5>{{ __('Scalable') }}</h5><p class="mb-0">{{ __('Bulk workflows with usage tracking so you can grow volume confidently.') }}</p></div></div>
            </div>
        </div>
    </section>

    {{-- VISUAL (OTP/message preview) --}}
    <section class="section">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="section-title mb-0">
                        <h2 class="mb-3">{{ __('Visual: OTP / message preview') }}</h2>
                        <p class="mb-0">{{ __('A simple message preview teams use to validate formatting and delivery.') }}</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="80">
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                        <img
                            src="{{ asset('front/images/landify/services/services-10.webp') }}"
                            class="img-fluid object-fit-cover"
                            alt="OTP and message preview illustration"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="call-to-action section dark-background">
        <div class="container text-center">
            <h2 class="mb-3">Launch your WhatsApp + SMS messaging stack in days</h2>
            <p class="opacity-75 mb-4">{{ __('Get API access and start with OTP, then expand to alerts and bulk workflows as you grow.') }}</p>
            <a href="{{ route('register', ['service' => 'api']) }}" class="btn btn-cta btn-lg px-5" data-track-event="hero_cta_click">Get API Access</a>
        </div>
    </section>
@endsection
