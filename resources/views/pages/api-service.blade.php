@extends('layouts.landing')

@section('meta_title', 'OTP & WhatsApp API Service | Fast & Secure Delivery')
@section('meta_description', 'Integrate our powerful OTP and WhatsApp API into your business. High delivery rates, instant connectivity, and easy developers integration.')
@section('meta_keywords', 'WhatsApp API, OTP Service, SMS API, Business Notifications, Developer API')

@section('content')
    @php
        $landingCta = static function (array $query = []): string {
            if (auth()->check()) {
                return $query === [] ? route('admin.dashboard') : route('admin.dashboard', $query);
            }
            return $query === [] ? route('register') : route('register', $query);
        };
    @endphp

    {{-- SECTION 1 — HERO --}}
    <section id="api-hero" class="hero section hero-section-enhanced hero-section-unique">
        <div class="hero-ambient hero-ambient--one" aria-hidden="true"></div>
        <div class="hero-ambient hero-ambient--two" aria-hidden="true"></div>
        <div class="hero-ambient hero-ambient--grid" aria-hidden="true"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-2 order-lg-1 hero-content-col" data-aos="fade-up" data-aos-duration="700">
                    <div class="hero-content">
                        <h1 class="hero-title">Enterprise-Grade OTP & WhatsApp API for Seamless Communication</h1>
                        <p class="hero-description">Send OTPs, notifications, and WhatsApp messages instantly with our powerful and scalable API built for modern businesses.</p>
                        <div class="hero-actions">
                            <a href="{{ $landingCta(['service' => 'api']) }}" class="btn-primary btn-hero-primary">Get API Access</a>
                            <a href="#api-preview" class="btn-secondary btn-hero-secondary ms-3">View Documentation</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="hero-visual">
                        <div class="hero-visual-frame p-4">
                            <div class="api-mock-container bg-white rounded-4 overflow-hidden shadow-lg" style="min-height: 320px; border: 1px solid color-mix(in srgb, var(--accent-color), transparent 85%);">
                                <div class="mock-header bg-light p-3 border-bottom d-flex align-items-center gap-2">
                                    <div class="bg-success rounded-circle" style="width: 10px; height: 10px;"></div>
                                    <span class="small fw-bold text-secondary">WhatsApp API Live</span>
                                </div>
                                <div class="mock-body p-3">
                                    <div class="whatsapp-msg-bubble bg-success text-white p-2 px-3 rounded-3 mb-3 d-inline-block shadow-sm" style="max-width: 85%; font-size: 0.9rem;">
                                        Your OTP for login is <strong>6429</strong>. Valid for 5 minutes.
                                    </div>
                                    <div class="api-json-block bg-dark text-info p-3 rounded-3 mt-4" style="font-family: 'Courier New', Courier, monospace; font-size: 0.85rem;">
                                        <div class="text-white-50">// POST /api/send-otp</div>
                                        <div>{</div>
                                        <div class="ms-3">"status": <span class="text-success">"delivered"</span>,</div>
                                        <div class="ms-3">"message_id": <span class="text-warning">"wa_92831"</span>,</div>
                                        <div class="ms-3">"timestamp": <span class="text-warning">"2026-04-14"</span></div>
                                        <div>}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 2 — SERVICE HIGHLIGHTS --}}
    <section id="api-highlights" class="section light-background features-cards-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Why Use Our API</span>
            <h2>Built for Reliability & Speed</h2>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="landing-feature-card">
                        <div class="landing-feature-card-icon"><i class="bi bi-shield-lock"></i></div>
                        <h5>High Security & Encryption</h5>
                        <p>Enterprise-level encryption for every message and OTP to ensure complete data privacy.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
                    <div class="landing-feature-card">
                        <div class="landing-feature-card-icon"><i class="bi bi-lightning"></i></div>
                        <h5>Instant Delivery (<5 sec)</h5>
                        <p>Our low-latency infrastructure ensures your OTPs land in seconds, reducing drop-offs.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
                    <div class="landing-feature-card">
                        <div class="landing-feature-card-icon"><i class="bi bi-globe"></i></div>
                        <h5>Global Reach</h5>
                        <p>Deliver WhatsApp messages and OTPs to customers worldwide across 190+ countries.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
                    <div class="landing-feature-card">
                        <div class="landing-feature-card-icon"><i class="bi bi-code-slash"></i></div>
                        <h5>Easy API Integration</h5>
                        <p>Developer-friendly REST API documentation with sample code for PHP, Python, and Node.js.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 3 — API PREVIEW --}}
    <section id="api-preview" class="section product-preview-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Developer Experience</span>
            <h2>Simple Integration. Zero Friction.</h2>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9" data-aos="fade-up">
                    <div class="code-block-wrapper bg-dark p-4 p-md-5 rounded-4 shadow-lg position-relative" style="background-color: #0f172a !important; border: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary px-3 rounded-pill">POST /api/send-otp</span>
                            <button class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="copyApiCode(this)">
                                <i class="bi bi-clipboard me-1"></i> Copy Code
                            </button>
                        </div>
                        <pre class="text-info m-0" style="font-family: 'Fira Code', 'Courier New', monospace; font-size: 1rem; line-height: 1.6;">
<span class="text-secondary">{</span>
    <span class="text-warning">"api_key"</span>: <span class="text-success">"your_api_key_here"</span>,
    <span class="text-warning">"phone"</span>: <span class="text-success">"919876543210"</span>,
    <span class="text-warning">"template"</span>: <span class="text-success">"otp_verification"</span>,
    <span class="text-warning">"variables"</span>: <span class="text-secondary">{</span>
        <span class="text-warning">"code"</span>: <span class="text-success">"6429"</span>
    <span class="text-secondary">}</span>
<span class="text-secondary">}</span></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 4 — USE CASES --}}
    <section id="api-use-cases" class="section use-cases-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Applications</span>
            <h2>One API. Endless Possibilities.</h2>
        </div>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-person-check"></i>
                        <h6>User Authentication (OTP)</h6>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="80">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-cart-check"></i>
                        <h6>Order Notifications</h6>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="160">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-wallet2"></i>
                        <h6>Payment Alerts</h6>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="240">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-megaphone"></i>
                        <h6>Marketing Broadcasts</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 5 — PRICING --}}
    <section id="api-pricing" class="section light-background pricing-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Pricing</span>
            <h2>Simple, Transparent Pricing</h2>
        </div>
        @include('partials.pricing-plans-grid', [
            'plans' => $pricingPlans ?? [],
            'ctaMode' => 'landing',
            'landingCta' => $landingCta,
            'enableAos' => true,
        ])
    </section>

    {{-- SECTION 6 — FINAL CTA --}}
    <section id="api-final-cta" class="call-to-action section dark-background final-cta-section">
        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="mb-3">Start Sending OTP & WhatsApp Messages Today</h2>
                    <p class="mb-4 opacity-90">Scale your communication with the most reliable API in the industry. Reliable, fast, and secure.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ $landingCta(['service' => 'api']) }}" class="btn btn-cta btn-cta-pulse btn-lg px-5 rounded-pill">Get Started</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function copyApiCode(btn) {
        const pre = btn.closest('.code-block-wrapper').querySelector('pre');
        const textToCopy = pre.innerText;
        navigator.clipboard.writeText(textToCopy).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-light');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-light');
            }, 2000);
        });
    }
</script>
@endpush
