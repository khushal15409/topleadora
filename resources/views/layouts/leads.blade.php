<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  @php
    $defaultTitle = __('Get matched — ') . config('app.name');
    $defaultDescription = __('Tell us what you need — we connect you with the right partners.');
    $pageTitle = trim($__env->yieldContent('meta_title')) ?: $defaultTitle;
    $pageDescription = trim($__env->yieldContent('meta_description')) ?: $defaultDescription;
    $pageKeywords = trim($__env->yieldContent('meta_keywords')) ?: 'leads, apply, quote, services';
    $canonicalSection = trim($__env->yieldContent('canonical_url'));
    $canonicalUrl = $canonicalSection !== '' ? $canonicalSection : url()->current();
    $socialImage = trim($__env->yieldContent('meta_og_image')) ?: \App\Support\SeoMeta::defaultOgImageUrl();
  @endphp

  <title>{{ $pageTitle }}</title>
  <meta name="description" content="{{ $pageDescription }}">
  <meta name="keywords" content="{{ $pageKeywords }}">
  <meta name="robots" content="@yield('meta_robots', config('leads.default_meta_robots', 'index,follow'))">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="canonical" href="{{ $canonicalUrl }}">
  @if (filled(config('leads.google_site_verification')))
    <meta name="google-site-verification" content="{{ config('leads.google_site_verification') }}">
  @endif
  @include('layouts.partials.favicon')

  <meta property="og:title" content="{{ $pageTitle }}">
  <meta property="og:description" content="{{ $pageDescription }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ $canonicalUrl }}">
  <meta property="og:image" content="{{ $socialImage }}">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $pageTitle }}">
  <meta name="twitter:description" content="{{ $pageDescription }}">
  <meta name="twitter:image" content="{{ $socialImage }}">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <link href="{{ asset('front/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('front/css/leads-saas.css') }}" rel="stylesheet">

  @stack('styles')

  @stack('json_ld')

  @include('layouts.partials.analytics')
</head>

<body class="leads-layout @yield('body_class')">

  @include('leads.partials.navbar')

  <main id="main">
    @yield('content')
  </main>

  @include('leads.partials.footer')

  <div id="leads-toast-host" class="leads-toast-host position-fixed top-0 start-50 translate-middle-x w-100 px-2"
    style="max-width: 480px;" aria-live="polite" aria-atomic="true"></div>
  <script>
    // Backwards-compatible alias used by some leads components.
    // Centralized toaster is injected below via layouts.partials.toaster.
    window.leadsShowToast = function (message, type) {
      if (!message) return;
      type = type || 'success';
      if (window.toaster && typeof window.toaster[type] === 'function') {
        window.toaster[type](message);
        return;
      }
    };
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"
    defer></script>
  <script defer>
    document.addEventListener('DOMContentLoaded', function () {
      var nav = document.querySelector('.navbar-leads');
      if (!nav) return;
      function onScroll() {
        nav.classList.toggle('navbar-scrolled', window.scrollY > 12);
      }
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    });
  </script>
  <script defer>
    document.addEventListener('DOMContentLoaded', function () {
      if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.ls-animate').forEach(function (el) {
          el.classList.add('is-visible');
        });
        return;
      }
      var obs = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (e) {
            if (e.isIntersecting) {
              e.target.classList.add('is-visible');
              obs.unobserve(e.target);
            }
          });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
      );
      document.querySelectorAll('.ls-animate').forEach(function (el) {
        obs.observe(el);
      });
    });
  </script>

  @stack('scripts')

  @include('layouts.partials.toaster')

  @if (session()->pull('track_google_conversion'))
    <script>
      trackGoogleConversion();
    </script>
  @endif
</body>

</html>