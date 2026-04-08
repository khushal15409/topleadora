@extends('layouts.landing')

@section('meta_title', 'Best WhatsApp CRM Software in India | Lead Management System')
@section('meta_description', 'Manage leads from WhatsApp, track sales pipelines, and automate follow-ups with the best CRM software in India. Start your free trial today.')
@section('meta_keywords', 'WhatsApp CRM, Lead Management Software, CRM India, Sales CRM, Real Estate CRM')

@section('content')
    @push('styles')
        <style>
            /* Remove any visual frame around hero image */
            .hero-image-wrapper,
            .hero-dashboard-float {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                outline: none !important;
                padding: 0 !important;
                border-radius: 0 !important;
                overflow: visible !important;
            }

            /* Make hero image larger and clean */
            .hero-image {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                object-fit: contain !important;
                display: block;
                margin: 0 auto;
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            /* Hide floating title cards over image as requested */
            .hero-image-wrapper .floating-elements {
                display: none !important;
            }
        </style>
    @endpush

    @php
        $landingCta = static function (array $query = []): string {
            if (auth()->check()) {
                return $query === [] ? route('admin.dashboard') : route('admin.dashboard', $query);
            }

            return $query === [] ? route('register') : route('register', $query);
        };
    @endphp
    {{-- SECTION 1 — HERO --}}
    <section id="hero" class="hero section hero-section-enhanced">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-2 order-lg-1 hero-content-col" data-aos="fade-up" data-aos-duration="700"
                    data-aos-delay="100">
                    <div class="hero-content">
                        <h1 class="hero-title">WhatsApp CRM to Capture, Manage &amp; Close More Leads Faster</h1>
                        <p class="hero-description">Manage leads from WhatsApp, Instagram, Facebook &amp; website in one
                            powerful CRM. Never miss a follow-up and close more deals faster.</p>
                        <div class="hero-actions">
                            <a href="{{ $landingCta() }}" class="btn-primary btn-hero-primary" onclick="trackGoogleConversion()">Start Free Trial (7
                                Days)</a>
                            <a href="#product-preview" class="btn-secondary btn-hero-secondary ms-3">Watch Demo</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="hero-visual">
                        <div class="hero-image-wrapper hero-dashboard-float">
                            <img src="{{ asset('front/images/landify/sections-images/hero.png') }}"
                                class="img-fluid hero-image" alt="WP-CRM dashboard preview" loading="eager">
                            <div class="floating-elements">
                                <div class="floating-card card-1 hero-float-card">
                                    <i class="bi bi-lightbulb"></i>
                                    <span>Smart Leads</span>
                                </div>
                                <div class="floating-card card-2 hero-float-card">
                                    <i class="bi bi-kanban"></i>
                                    <span>Pipeline</span>
                                </div>
                                <div class="floating-card card-3 hero-float-card">
                                    <i class="bi bi-people"></i>
                                    <span>Team</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 2 — SOCIAL PROOF --}}
    <section id="social-proof" class="section light-background social-proof-section py-4">
        <div class="container">
            <div class="section-title text-center mb-4" data-aos="fade-up">
                <h2 class="mb-0 social-proof-title">Trusted by Growing Sales Teams &amp; Agencies</h2>
            </div>
            <div class="row g-4 justify-content-center text-center social-proof-stats">
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="social-stat-item">
                        <div class="social-stat-value">1000+</div>
                        <div class="social-stat-label">Leads Managed Daily</div>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="social-stat-item">
                        <div class="social-stat-value">98%</div>
                        <div class="social-stat-label">Customer Satisfaction</div>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="social-stat-item">
                        <div class="social-stat-value">24/7</div>
                        <div class="social-stat-label">Support</div>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="social-stat-item">
                        <div class="social-stat-value">100%</div>
                        <div class="social-stat-label">Secure Platform</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 3 — PROBLEM --}}
    <section id="problems" class="section problem-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Challenges</span>
            <h2>Struggling to Manage Leads on WhatsApp?</h2>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-right" data-aos-delay="0">
                    <div class="feature-box h-100">
                        <div class="feature-icon"><i class="bi bi-chat-square-text"></i></div>
                        <h5>Leads get lost in chats</h5>
                        <p>Important enquiries disappear in endless threads with no single source of truth.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-right" data-aos-delay="80">
                    <div class="feature-box h-100">
                        <div class="feature-icon"><i class="bi bi-bell-slash"></i></div>
                        <h5>No proper follow-up system</h5>
                        <p>Without reminders and ownership, hot leads go cold before anyone responds.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-right" data-aos-delay="160">
                    <div class="feature-box h-100">
                        <div class="feature-icon"><i class="bi bi-graph-down-arrow"></i></div>
                        <h5>Hard to track sales progress</h5>
                        <p>Spreadsheets and guesswork make it impossible to see pipeline health at a glance.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-right" data-aos-delay="240">
                    <div class="feature-box h-100">
                        <div class="feature-icon"><i class="bi bi-diagram-3"></i></div>
                        <h5>Multiple platforms create confusion</h5>
                        <p>WhatsApp, DMs, and web forms live in silos — teams lose context and speed.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 4 — SOLUTION --}}
    <section id="solution" class="section light-background solution-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Solution</span>
            <h2>All Your Leads. One Powerful CRM.</h2>
        </div>
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-md-6 col-lg-3" data-aos="fade-left" data-aos-delay="0">
                    <div class="feature-box h-100 solution-card">
                        <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                        <h5>Capture leads automatically</h5>
                        <p>Pull conversations and enquiries into structured records the moment they arrive.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-left" data-aos-delay="80">
                    <div class="feature-box h-100 solution-card">
                        <div class="feature-icon"><i class="bi bi-folder2-open"></i></div>
                        <h5>Organize conversations</h5>
                        <p>Tags, stages, and history so every rep knows what was said and what’s next.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-left" data-aos-delay="160">
                    <div class="feature-box h-100 solution-card">
                        <div class="feature-icon"><i class="bi bi-kanban"></i></div>
                        <h5>Track every deal</h5>
                        <p>Visual pipeline from first message to won — priorities stay clear for the whole team.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-left" data-aos-delay="240">
                    <div class="feature-box h-100 solution-card">
                        <div class="feature-icon"><i class="bi bi-alarm"></i></div>
                        <h5>Never miss follow-ups</h5>
                        <p>Smart reminders and assignments keep response times fast and consistent.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 5 — HOW IT WORKS --}}
    <section id="how-it-works" class="section how-it-works-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Process</span>
            <h2>Simple. Smart. Effective.</h2>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature-box h-100 step-card">
                        <div class="step-badge">1</div>
                        <div class="feature-icon"><i class="bi bi-funnel"></i></div>
                        <h5>Capture Leads</h5>
                        <p>Connect channels and let every enquiry land in one inbox automatically.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="120">
                    <div class="feature-box h-100 step-card">
                        <div class="step-badge">2</div>
                        <div class="feature-icon"><i class="bi bi-kanban-fill"></i></div>
                        <h5>Manage Pipeline</h5>
                        <p>Move deals across stages, assign owners, and collaborate without leaving the CRM.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="240">
                    <div class="feature-box h-100 step-card">
                        <div class="step-badge">3</div>
                        <div class="feature-icon"><i class="bi bi-trophy"></i></div>
                        <h5>Close Deals</h5>
                        <p>Follow up on time, broadcast when it fits, and win more with full visibility.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 6 — FEATURES (cards + existing Landify tabs) --}}
    <section id="features" class="section light-background features-cards-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Features</span>
            <h2>Everything You Need to Close More Deals</h2>
        </div>
        <div class="container">
            <div class="row gy-4 mb-5">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="landing-feature-card h-100">
                        <div class="landing-feature-card-icon"><i class="bi bi-people"></i></div>
                        <h5>Lead Management</h5>
                        <p>Capture, qualify, and nurture every lead with full history and smart filters.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="80">
                    <div class="landing-feature-card h-100">
                        <div class="landing-feature-card-icon"><i class="bi bi-kanban"></i></div>
                        <h5>Sales Pipeline</h5>
                        <p>Custom stages, drag-and-drop clarity, and conversion tracking your team will actually use.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="160">
                    <div class="landing-feature-card h-100">
                        <div class="landing-feature-card-icon"><i class="bi bi-bell"></i></div>
                        <h5>Follow-ups</h5>
                        <p>Automated reminders and tasks so no opportunity slips through the cracks.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 offset-lg-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="landing-feature-card h-100">
                        <div class="landing-feature-card-icon"><i class="bi bi-broadcast"></i></div>
                        <h5>Broadcast Messaging</h5>
                        <p>Reach the right segments on WhatsApp with templates, scheduling, and delivery insight.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="180">
                    <div class="landing-feature-card h-100">
                        <div class="landing-feature-card-icon"><i class="bi bi-people-fill"></i></div>
                        <h5>Team Collaboration</h5>
                        <p>Assign leads, share notes, and keep everyone accountable in one workspace.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features section" aria-label="Feature details">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Deep dive</span>
            <h2>Explore Capabilities</h2>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="tabs-wrapper">
                <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">
                    <li class="nav-item">
                        <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                            <div class="tab-icon"><i class="bi bi-people"></i></div>
                            <div class="tab-content">
                                <h5>Lead Management</h5>
                                <span>Capture & nurture</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                            <div class="tab-icon"><i class="bi bi-kanban"></i></div>
                            <div class="tab-content">
                                <h5>Pipeline</h5>
                                <span>Visual stages</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
                            <div class="tab-icon"><i class="bi bi-broadcast"></i></div>
                            <div class="tab-content">
                                <h5>Broadcast</h5>
                                <span>Bulk messaging</span>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" data-aos="fade-up" data-aos-delay="200">
                    <div class="tab-pane fade active show" id="features-tab-1">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="content-wrapper">
                                    <div class="icon-badge"><i class="bi bi-people"></i></div>
                                    <h3>Lead Management</h3>
                                    <p>Turn WhatsApp and omnichannel enquiries into structured leads. Capture, qualify, and
                                        nurture without missing a single opportunity.</p>
                                    <div class="feature-grid">
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Auto capture
                                                leads</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Assign owners
                                                &amp; notes</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Complete
                                                activity history</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Smart filters
                                                &amp; search</span></div>
                                    </div>
                                    <a href="{{ $landingCta() }}" class="btn-primary">Get Started <i
                                            class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="visual-content">
                                    <div class="main-image">
                                        <img src="{{ asset('front/images/landify/features/features-4.png') }}" alt="Leads"
                                            class="img-fluid" loading="lazy">
                                        <div class="floating-card">
                                            <i class="bi bi-graph-up-arrow"></i>
                                            <div class="card-content">
                                                <span>Conversion</span>
                                                <strong>+40% Lead Conversion</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="features-tab-2">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="content-wrapper">
                                    <div class="icon-badge"><i class="bi bi-kanban"></i></div>
                                    <h3>Pipeline Management</h3>
                                    <p>Visual stages for any sales process. See where every lead stands and what to do
                                        next.</p>
                                    <div class="feature-grid">
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Custom sales
                                                stages</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Drag &amp;
                                                drop leads</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Conversion
                                                tracking</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Deal status
                                                clarity</span></div>
                                    </div>
                                    <a href="{{ $landingCta() }}" class="btn-primary">Try Pipeline <i
                                            class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="visual-content">
                                    <div class="main-image">
                                        <img src="{{ asset('front/images/landify/features/features-2.webp') }}"
                                            alt="Pipeline" class="img-fluid" loading="lazy">
                                        <div class="floating-card">
                                            <i class="bi bi-kanban"></i>
                                            <div class="card-content">
                                                <span>Stages</span>
                                                <strong>Fully Customizable</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="features-tab-3">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="content-wrapper">
                                    <div class="icon-badge"><i class="bi bi-broadcast"></i></div>
                                    <h3>Broadcast Messaging</h3>
                                    <p>Send offers, updates, and follow-ups at scale. Reach the right people at the right
                                        time.</p>
                                    <div class="feature-grid">
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Bulk WhatsApp
                                                campaigns</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Campaign
                                                scheduling</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Delivery
                                                tracking</span></div>
                                        <div class="feature-item"><i class="bi bi-check-circle-fill"></i><span>Plan-based
                                                access</span></div>
                                    </div>
                                    <a href="{{ $landingCta() }}" class="btn-primary">Start Broadcasting <i
                                            class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="visual-content">
                                    <div class="main-image">
                                        <img src="{{ asset('front/images/landify/features/features-6.webp') }}"
                                            alt="Broadcasts" class="img-fluid" loading="lazy">
                                        <div class="floating-card">
                                            <i class="bi bi-broadcast"></i>
                                            <div class="card-content">
                                                <span>Reach</span>
                                                <strong>Thousands at Once</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 7 — OMNICHANNEL --}}
    <section id="omnichannel" class="section light-background omnichannel-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Omnichannel</span>
            <h2>Capture Leads from Every Platform</h2>
        </div>
        <div class="container">
            <div class="row g-4 justify-content-center omnichannel-grid">
                <div class="col-6 col-sm-4 col-md-2 text-center" data-aos="zoom-in" data-aos-delay="0">
                    <div class="omni-channel-item">
                        <i class="bi bi-whatsapp"></i>
                        <span>WhatsApp</span>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2 text-center" data-aos="zoom-in" data-aos-delay="60">
                    <div class="omni-channel-item">
                        <i class="bi bi-instagram"></i>
                        <span>Instagram</span>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2 text-center" data-aos="zoom-in" data-aos-delay="120">
                    <div class="omni-channel-item">
                        <i class="bi bi-facebook"></i>
                        <span>Facebook</span>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2 text-center" data-aos="zoom-in" data-aos-delay="180">
                    <div class="omni-channel-item">
                        <i class="bi bi-globe2"></i>
                        <span>Website</span>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2 text-center" data-aos="zoom-in" data-aos-delay="240">
                    <div class="omni-channel-item">
                        <i class="bi bi-code-slash"></i>
                        <span>API</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 8 — PRODUCT PREVIEW --}}
    <section id="product-preview" class="section product-preview-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Product</span>
            <h2>Powerful Dashboard Built for Sales Teams</h2>
        </div>
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm preview-zoom-wrap">
                        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="WP-CRM product demo"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <p class="lead-text mb-4">See how teams centralize WhatsApp and omnichannel leads, run pipelines, and
                        act on reminders — without switching tools.</p>
                    <ul class="feature-list list-unstyled mb-4">
                        <li><i class="bi bi-check-circle"></i> Unified inbox &amp; lead profiles</li>
                        <li><i class="bi bi-check-circle"></i> Real-time pipeline &amp; reports</li>
                        <li><i class="bi bi-check-circle"></i> Built for speed and clarity</li>
                    </ul>
                    <a href="{{ $landingCta() }}" class="btn btn-cta">Start Your Free Trial</a>
                </div>
            </div>
            <div class="row g-4 mt-2 preview-screens-row">
                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="0">
                    <div class="preview-screen-card">
                        <img src="{{ asset('front/images/landify/about/about-8.webp') }}" class="img-fluid rounded-3"
                            alt="Dashboard overview" loading="lazy">
                    </div>
                </div>
                <div class="col-md-6" data-aos="zoom-in" data-aos-delay="120">
                    <div class="preview-screen-card">
                        <img src="{{ asset('front/images/landify/about/about-11.webp') }}" class="img-fluid rounded-3"
                            alt="Pipeline view" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 9 — BENEFITS --}}
    <section id="benefits" class="section light-background benefits-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Benefits</span>
            <h2>Why Choose Our CRM?</h2>
        </div>
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="benefit-item text-center">
                        <div class="benefit-icon-wrap"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5>Increase conversion</h5>
                        <p class="mb-0">Faster responses and clearer next steps turn more chats into revenue.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="benefit-item text-center">
                        <div class="benefit-icon-wrap"><i class="bi bi-clock-history"></i></div>
                        <h5>Save time</h5>
                        <p class="mb-0">Automation and one inbox cut manual work so you sell, not sort messages.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-item text-center">
                        <div class="benefit-icon-wrap"><i class="bi bi-speedometer2"></i></div>
                        <h5>Improve productivity</h5>
                        <p class="mb-0">Everyone sees the same truth — less back-and-forth, more closed deals.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="benefit-item text-center">
                        <div class="benefit-icon-wrap"><i class="bi bi-hand-thumbs-up"></i></div>
                        <h5>Easy to use</h5>
                        <p class="mb-0">Clean UI your team adopts in days, not months — minimal training required.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 10 — USE CASES --}}
    <section id="use-cases" class="section use-cases-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Industries</span>
            <h2>Built for Every Growing Business</h2>
        </div>
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="0">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-building"></i>
                        <h6>Real Estate</h6>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="80">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-shield-check"></i>
                        <h6>Insurance</h6>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="160">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-briefcase"></i>
                        <h6>Agencies</h6>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="240">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-mortarboard"></i>
                        <h6>Coaches</h6>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg" data-aos="fade-up" data-aos-delay="320">
                    <div class="use-case-card text-center h-100">
                        <i class="bi bi-shop"></i>
                        <h6>Small Business</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 11 — TESTIMONIALS --}}
    <section id="testimonials" class="section light-background testimonials-section">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">Testimonials</span>
            <h2>What Our Users Say</h2>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper init-swiper testimonials-swiper pb-5">
                <script type="application/json" class="swiper-config">
                    {
                        "loop": true,
                        "speed": 650,
                        "autoplay": { "delay": 4800, "disableOnInteraction": false },
                        "slidesPerView": 1,
                        "spaceBetween": 24,
                        "pagination": { "el": ".testimonials-swiper .swiper-pagination", "clickable": true },
                        "breakpoints": { "768": { "slidesPerView": 2 }, "992": { "slidesPerView": 3 } }
                    }
                </script>
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-card h-100">
                            <div class="testimonial-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i></div>
                            <p class="testimonial-text">“We stopped losing enquiries in WhatsApp. Pipeline view alone paid
                                for the tool in the first month.”</p>
                            <div class="testimonial-author">
                                <strong>Priya N.</strong>
                                <span>Agency Owner, Mumbai</span>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card h-100">
                            <div class="testimonial-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i></div>
                            <p class="testimonial-text">“Follow-up reminders are a game changer. Our response time dropped
                                from hours to minutes.”</p>
                            <div class="testimonial-author">
                                <strong>Rahul V.</strong>
                                <span>Sales Lead, Bengaluru</span>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card h-100">
                            <div class="testimonial-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i></div>
                            <p class="testimonial-text">“Finally one place for Instagram DMs and WhatsApp leads. Onboarding
                                was surprisingly easy.”</p>
                            <div class="testimonial-author">
                                <strong>Anita S.</strong>
                                <span>Real Estate Consultant</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    {{-- SECTION 12 — PRICING (shared with admin Plans & billing) --}}
    @include('partials.pricing-plans-grid', [
        'plans' => $pricingPlans,
        'ctaMode' => 'landing',
        'landingCta' => $landingCta,
        'enableAos' => true,
    ])

    {{-- SECTION — CONTACT --}}
    <section id="contact" class="contact section light-background">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">{{ __('Contact') }}</span>
            <h2>{{ __('Get in Touch') }}</h2>
            <p>{{ __('Have a question or want a demo? Send us a message.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @include('partials.contact-form', ['contactFormReturn' => 'landing'])
                </div>
            </div>
            <p class="text-center small text-muted mt-3 mb-0">
                <a href="{{ route('contact') }}">{{ __('Open full contact page') }}</a>
            </p>
        </div>
    </section>

    {{-- SECTION 13 — FAQ --}}
    <section id="faq" class="contact faq-section section light-background">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">FAQ</span>
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers about getting started and security.</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion faq-accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                    What is WhatsApp CRM?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    A WhatsApp CRM brings leads, chats, and your sales pipeline into one workspace so your
                                    team can respond faster and close more deals.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                    Is the 7-day trial really free?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. Full access for seven days with no credit card required. Upgrade only when you’re
                                    ready.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                    Can I connect Instagram and Facebook leads too?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes. WP-CRM is built for omnichannel capture so enquiries from social and your website
                                    flow into the same inbox and pipeline.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                    Is my customer data secure?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We use secure infrastructure, encryption in transit and at rest where applicable, and
                                    regular backups so your data stays protected.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                                    Can I change plans later?
                                </button>
                            </h3>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely. Move up or down as your team size and lead volume change — you’re never
                                    locked in.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center mt-4 mb-0">Still have questions? <a href="{{ $landingCta() }}">Start free</a>
                or <a href="{{ url('/blog') }}">read our blog</a>.</p>
        </div>
    </section>

    {{-- SECTION 14 — FINAL CTA --}}
    <section id="final-cta" class="call-to-action section dark-background final-cta-section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="mb-3">Start Managing Your Leads the Smart Way</h2>
                    <p class="mb-4 opacity-90">Join teams who replaced scattered chats with one CRM — clearer pipeline, faster
                        follow-ups, more wins.</p>
                    <a href="{{ $landingCta() }}" class="btn btn-cta btn-cta-pulse btn-lg px-5">Start Free Trial
                        Now</a>
                </div>
            </div>
        </div>
    </section>
@endsection
