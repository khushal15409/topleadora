<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @php
        $defaultTitle = 'WhatsApp CRM, API & Lead Generation India';
        $defaultDescription = 'WhatsApp CRM software India, API messaging, and lead generation services India for real estate, insurance, and agencies.';
        $defaultKeywords = 'whatsapp crm software india, whatsapp api provider india, sms api india, lead generation services india';
        $pageTitle = trim($__env->yieldContent('meta_title')) ?: $defaultTitle;
        $pageDescription = trim($__env->yieldContent('meta_description')) ?: $defaultDescription;
        $pageKeywords = trim($__env->yieldContent('meta_keywords')) ?: $defaultKeywords;
        $canonicalSection = trim($__env->yieldContent('canonical_url'));
        // Prefer explicit canonical from each view to avoid ?query variants diluting signals.
        $canonicalUrl = $canonicalSection !== '' ? $canonicalSection : url()->current();
        // Single config-driven OG fallback (config/seo.php + SeoMeta::defaultOgImageUrl).
        $socialImage = trim($__env->yieldContent('meta_og_image')) ?: \App\Support\SeoMeta::defaultOgImageUrl();
        $socialType = trim($__env->yieldContent('meta_og_type')) ?: 'website';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    {{-- meta keywords are ignored by Google; kept for legacy tools only --}}
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @include('layouts.partials.favicon')

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="{{ $socialType }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $socialImage }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $socialImage }}">

    <!-- JSON-LD Structured Data -->
    @php
        $orgLd = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    'name' => 'WhatsAppLeadCRM',
                    'url' => url('/'),
                    'logo' => asset('front/images/logo.png'),
                    'description' => $pageDescription,
                ],
                [
                    '@type' => 'WebSite',
                    'name' => 'WhatsAppLeadCRM',
                    'url' => url('/'),
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($orgLd, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>

    @stack('json_ld')

    <!-- Fonts (Landify) -->
    {{-- TODO: Consider self-hosting font files or using font subsetting to improve LCP and reduce third-party requests.
    --}}
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    {{-- Self-hosted icons so fonts resolve on same origin (fixes missing icons when CDN/subpath blocks font files) --}}
    <link href="{{ asset('front/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    {{-- Non-render-blocking: improves FCP/LCP vs synchronous third-party CSS (see Core Web Vitals). --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </noscript>
    <link href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css" rel="stylesheet"
        crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css" rel="stylesheet"
            crossorigin="anonymous">
    </noscript>
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" crossorigin="anonymous"
        media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet"
            crossorigin="anonymous">
    </noscript>
    {{-- TODO: Audit defer/async and bundle strategy for AOS, GLightbox, Swiper if CLS or main-thread time regress. --}}

    <!-- Landify theme CSS -->
    <link href="{{ asset('front/css/landify-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/landing-custom.css') }}" rel="stylesheet">

    @stack('styles')

    @include('layouts.partials.google-ads')
</head>

<body class="@yield('body_class', 'index-page')">

    @php
        $hash = static fn(string $id) => request()->is('/') ? '#' . $id : url('/#' . $id);
        $getStartedHref = auth()->check() ? route('admin.dashboard') : route('register');
        // Header "Pricing" menu should default to HIDE if setting is missing.
        // Admin toggle: settings.payment_enabled (DB).
        $paymentEnabled = (string) setting('payment_enabled', '0');
        $paymentEnabled = ($paymentEnabled === '1' || $paymentEnabled === 1 || $paymentEnabled === true);
    @endphp

    <header id="header" class="header header-saas navbar-saas d-flex align-items-center fixed-top">
        <div
            class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between gap-3 py-1 py-lg-0">
            <a href="{{ url('/') }}" class="logo d-flex align-items-center flex-shrink-0 navbar-saas-logo">
                <img src="{{ asset('front/images/logo.png') }}" alt="WhatsApp CRM Software in India">
            </a>

            <nav id="navmenu" class="navmenu navbar-saas-center d-none d-xl-block mx-auto" aria-label="Primary">
                <ul>
                    <li>
                        <a href="{{ url('/') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a>
                    </li>
                    <li class="dropdown">
                        <a href="#">
                            <span>{{ __('Products') }}</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="{{ route('whatsapp-crm') }}" class="{{ request()->routeIs('whatsapp-crm') ? 'active' : '' }}">{{ __('WhatsApp CRM') }}</a></li>
                            <li><a href="{{ route('whatsapp-api') }}" class="{{ request()->routeIs('whatsapp-api') ? 'active' : '' }}">{{ __('WhatsApp API') }}</a></li>
                            <li><a href="{{ route('lead-generation') }}" class="{{ request()->routeIs('lead-generation') ? 'active' : '' }}">{{ __('Lead Generation') }}</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#">
                            <span>{{ __('Solutions') }}</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="{{ url('/leads/real-estate') }}">{{ __('Real Estate') }}</a></li>
                            <li><a href="{{ url('/leads/insurance') }}">{{ __('Insurance') }}</a></li>
                            <li><a href="{{ url('/agencies') }}">{{ __('Agencies') }}</a></li>
                            <li><a href="{{ url('/small-business') }}">{{ __('Small Business') }}</a></li>
                        </ul>
                    </li>

                    @if ($paymentEnabled)
                        <li><a href="{{ $hash('pricing') }}">{{ __('Pricing') }}</a></li>
                    @endif

                    <li class="dropdown">
                        <a href="#">
                            <span>{{ __('Resources') }}</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i>
                        </a>
                        <ul>
                            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a></li>
                            <li><a href="{{ url('/case-studies') }}">{{ __('Case Studies') }}</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('Contact') }}</a>
                    </li>
                </ul>
            </nav>

            <div class="d-flex align-items-center gap-2 gap-lg-3 flex-shrink-0 navbar-saas-actions">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn-navbar-cta d-none d-sm-inline-flex">Dashboard</a>
                    <a href="{{ route('admin.dashboard') }}"
                        class="btn-navbar-cta btn-navbar-cta-compact d-sm-none">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="navbar-saas-login d-none d-sm-inline-flex">Login</a>
                    <a href="{{ $getStartedHref }}" class="btn-navbar-cta d-none d-sm-inline-flex">Start Free Trial</a>
                    <a href="{{ $getStartedHref }}" class="btn-navbar-cta btn-navbar-cta-compact d-sm-none">Start Free Trial</a>
                @endauth
                <button class="btn btn-saas-menu d-xl-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#saasNavOffcanvas" aria-controls="saasNavOffcanvas" aria-label="Open menu">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>
        </div>
    </header>

    <div class="offcanvas offcanvas-end saas-nav-offcanvas" tabindex="-1" id="saasNavOffcanvas"
        aria-labelledby="saasNavOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title h5 mb-0" id="saasNavOffcanvasLabel">Menu</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <nav class="nav flex-column gap-1 saas-offcanvas-nav" aria-label="Mobile">
                <a class="nav-link saas-offcanvas-link" href="{{ url('/') }}">{{ __('Home') }}</a>
                <a class="nav-link saas-offcanvas-link" data-bs-toggle="collapse" href="#mobileProducts" role="button" aria-expanded="false" aria-controls="mobileProducts">
                    {{ __('Products') }}
                </a>
                <div class="collapse" id="mobileProducts">
                    <a class="nav-link saas-offcanvas-link" href="{{ route('whatsapp-crm') }}">{{ __('WhatsApp CRM') }}</a>
                    <a class="nav-link saas-offcanvas-link" href="{{ route('whatsapp-api') }}">{{ __('WhatsApp API') }}</a>
                    <a class="nav-link saas-offcanvas-link" href="{{ route('lead-generation') }}">{{ __('Lead Generation') }}</a>
                </div>

                <a class="nav-link saas-offcanvas-link" data-bs-toggle="collapse" href="#mobileSolutions" role="button" aria-expanded="false" aria-controls="mobileSolutions">
                    {{ __('Solutions') }}
                </a>
                <div class="collapse" id="mobileSolutions">
                    <a class="nav-link saas-offcanvas-link" href="{{ url('/leads/real-estate') }}">{{ __('Real Estate') }}</a>
                    <a class="nav-link saas-offcanvas-link" href="{{ url('/leads/insurance') }}">{{ __('Insurance') }}</a>
                    <a class="nav-link saas-offcanvas-link" href="{{ url('/agencies') }}">{{ __('Agencies') }}</a>
                    <a class="nav-link saas-offcanvas-link" href="{{ url('/small-business') }}">{{ __('Small Business') }}</a>
                </div>

                @if ($paymentEnabled)
                    <a class="nav-link saas-offcanvas-link" href="{{ $hash('pricing') }}">{{ __('Pricing') }}</a>
                @endif

                <a class="nav-link saas-offcanvas-link" data-bs-toggle="collapse" href="#mobileResources" role="button" aria-expanded="false" aria-controls="mobileResources">
                    {{ __('Resources') }}
                </a>
                <div class="collapse" id="mobileResources">
                    <a class="nav-link saas-offcanvas-link" href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
                    <a class="nav-link saas-offcanvas-link" href="{{ url('/case-studies') }}">{{ __('Case Studies') }}</a>
                </div>

                <a class="nav-link saas-offcanvas-link" href="{{ route('contact') }}">{{ __('Contact') }}</a>
            </nav>
            <div class="mt-auto pt-4 d-flex flex-column gap-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="btn btn-navbar-cta w-100 justify-content-center">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary rounded-pill">Login</a>
                    <a href="{{ $getStartedHref }}" class="btn btn-navbar-cta w-100 justify-content-center">Start Free Trial</a>
                @endauth
            </div>
        </div>
    </div>

    <main class="main">
        @yield('content')
    </main>

    <footer id="footer" class="footer position-relative dark-background landing-footer-enhanced">
        <div class="container footer-top">
            <div class="row gy-4 gx-lg-5 align-items-start">
                <div class="col-lg-5 col-md-12 footer-about">
                    <a href="{{ url('/') }}" class="logo d-flex align-items-center" style="max-width: 720px;">
                        <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name', 'WP-CRM') }}"
                            style="width: auto; height: 180px; max-height: 200px; object-fit: contain;">
                    </a>
                    <p>WhatsApp CRM for Real Estate & Sales. Manage leads, pipelines, and follow-ups in one place.</p>
                    {{-- Replace with real business data: add profile URLs (see config/branding.php social_* or
                    hard-code once verified). --}}
                    <div class="social-links d-flex mt-4 gap-2" aria-label="Social media">
                        {{-- Intentionally empty: placeholder # links hurt crawl budget and trust; wire real hrefs
                        before launch. --}}
                    </div>
                </div>
                <div class="col-lg-2 col-6 footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ url('/') }}#social-proof">Social proof</a></li>
                        <li><a href="{{ url('/') }}#features">Features</a></li>
                        @if ($paymentEnabled)
                            <li><a href="{{ url('/') }}#pricing">Pricing</a></li>
                        @endif
                        <li><a href="{{ url('/') }}#faq">FAQ</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 footer-links">
                    <h4>Account</h4>
                    <ul>
                        @auth
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Get started</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-2 col-6 footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="{{ route('privacy-policy') }}">Privacy policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms</a></li>
                        <li><a href="{{ route('refund-policy') }}">Refund policy</a></li>
                    </ul>
                </div>
                <div id="footer-contact" class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Contact</h4>
                    {{-- Replace with real business data: set SUPPORT_EMAIL in .env (config/branding.php). --}}
                    @if (filled(config('branding.support_email')))
                        <p class="mb-0">📧 <a href="mailto:{{ config('branding.support_email') }}"
                                class="link-light">{{ config('branding.support_email') }}</a></p>
                    @else
                        <p class="mb-0 small text-white-50">
                            <a class="link-light" href="{{ route('contact') }}">{{ __('Use our contact form') }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="container copyright text-center mt-4">
            <p>© <span>{{ now()->year }} {{ config('app.name', 'WP-CRM') }}</span> <span>{{ __('All rights reserved') }}</span></p>
        </div>
    </footer>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"
        defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js" crossorigin="anonymous"
        defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter.js" crossorigin="anonymous"
        defer></script>

    <!-- Landify theme JS -->
    <script src="{{ asset('front/js/landify.js') }}" defer></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            var panel = document.getElementById('saasNavOffcanvas');
            if (!panel || typeof bootstrap === 'undefined' || !bootstrap.Offcanvas) return;
            var oc = bootstrap.Offcanvas.getOrCreateInstance(panel);
            panel.querySelectorAll('a[href]').forEach(function (link) {
                link.addEventListener('click', function () {
                    oc.hide();
                });
            });
        });
    </script>

    @include('layouts.partials.toaster')

    @stack('scripts')

    @if (session()->pull('track_google_conversion'))
        <script>
            trackGoogleConversion();
        </script>
    @endif
</body>

</html>