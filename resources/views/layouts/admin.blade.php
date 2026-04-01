<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="layout-menu-fixed layout-compact"
    data-assets-path="{{ asset('materio/assets/') }}/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    >
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — {{ config('app.name', 'WP-CRM') }}</title>
    @include('layouts.partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/fonts/iconify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/node-waves/node-waves.css') }}">
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('materio/assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('materio/assets/css/admin-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('materio/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    @stack('vendor-css')

    @include('layouts.partials.google-ads')

    <script src="{{ asset('materio/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('materio/assets/js/config.js') }}"></script>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.partials.sidebar')

            <div class="layout-page">
                @include('layouts.partials.navbar')

                <div class="content-wrapper">
                    @include('layouts.partials.plan-expired-banner')
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @stack('page-header')
                        @yield('content')
                    </div>

                    @include('layouts.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="{{ asset('materio/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('materio/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('materio/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('materio/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('materio/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('materio/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('materio/assets/js/main.js') }}"></script>

    @stack('vendor-js')
    @stack('page-js')

    @if (session()->pull('track_google_conversion'))
        <script>
            trackGoogleConversion();
        </script>
    @endif
</body>
</html>
