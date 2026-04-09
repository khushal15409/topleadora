<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" class="light"
    data-header-styles="light" data-menu-styles="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', 'Admin Dashboard') — {{ config('app.name', 'WP-CRM') }} </title>

    <!-- Favicon -->
    @include('layouts.partials.favicon')

    @php
        $resolveBuildAsset = static function (string $pattern): ?string {
            $files = glob(public_path("build/assets/{$pattern}"));
            if (! $files) {
                return null;
            }
            usort($files, static fn ($a, $b) => filemtime($a) <=> filemtime($b));
            $file = end($files);
            return $file ? asset('build/assets/' . basename($file)) : null;
        };

        $adminAppCss = $resolveBuildAsset('app-*.css');
        $adminAppJs = $resolveBuildAsset('app-*.js');
        $adminCustomSwitcher = $resolveBuildAsset('custom-switcher-*.js');
    @endphp

    <!-- Main Theme Js -->
    <script src="{{ asset('build/assets/main.js') }}"></script>

    <!-- ICONS CSS -->
    <link href="{{asset('build/assets/iconfonts/icons.css')}}" rel="stylesheet">

    <!-- APP CSS & APP SCSS -->
    @if ($adminAppCss)
        <link rel="stylesheet" href="{{ $adminAppCss }}">
    @endif

    @include('layouts.components.styles')

    @stack('vendor-css')
    @stack('styles')
    @stack('json_ld')

    @include('layouts.partials.google-ads')

</head>

@php
    $saasUser = auth()->user();
    $saasIsSuper = $saasUser && $saasUser->hasRole(\App\Support\Roles::SUPER_ADMIN);
    $saasIsOrg = $saasUser && $saasUser->hasRole(\App\Support\Roles::ORGANIZATION);
@endphp

<body
    class="gcc-admin-theme {{ $saasIsSuper ? 'saas-admin--super' : '' }} {{ ($saasIsOrg && !$saasIsSuper) ? 'saas-admin--org' : '' }}">

    <!-- Switcher -->
    @include('layouts.components.switcher')
    <!-- End switcher -->

    <!-- Loader -->
    <div id="loader">
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
    </div>
    <!-- Loader -->

    <div class="page">

        <!-- Main-Header -->
        @include('layouts.components.main-header')
        <!-- End Main-Header -->

        <!-- Country-selector modal -->
        @include('layouts.components.modal')
        <!-- End Country-selector modal -->

        <!--Main-Sidebar-->
        @include('layouts.components.main-sidebar')
        <!-- End Main-Sidebar-->

        <!-- Start::content  -->
        <div class="content">
            <!-- Start::main-content -->
            <div class="main-content">
                @stack('page-header')

                @if (session()->pull('track_google_conversion'))
                    <script>
                        trackGoogleConversion();
                    </script>
                @endif

                @yield('content')

            </div>
        </div>
        <!-- End::content  -->

        <!-- Footer opened -->
        @include('layouts.components.footer')
        <!-- End Footer -->

        @yield('modals')

    </div>

    <!-- SCRIPTS -->
    @include('layouts.components.scripts')

    <!-- Sticky JS -->
    <script src="{{asset('build/assets/sticky.js')}}"></script>

    <!-- Custom-Switcher JS -->
    @if ($adminCustomSwitcher)
        <script type="module" src="{{ $adminCustomSwitcher }}"></script>
    @endif

    <!-- APP JS-->
    @if ($adminAppJs)
        <script type="module" src="{{ $adminAppJs }}"></script>
    @endif

    @stack('vendor-js')
    @stack('page-js')
    @stack('scripts')
    <!-- END SCRIPTS -->

</body>

</html>
