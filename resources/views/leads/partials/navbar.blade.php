@php
    $ctaLabel = __('Get free leads');
    $homeUrl = url('/');
@endphp

<header>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-leads">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $homeUrl }}">
                <img
                    src="{{ asset('front/images/logo.png') }}"
                    alt="{{ config('app.name') }}"
                    class="leads-navbar-logo"
                    width="320"
                    height="80"
                    decoding="async"
                    fetchpriority="high"
                >
            </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#leadsNav"
                aria-controls="leadsNav"
                aria-expanded="false"
                aria-label="{{ __('Toggle menu') }}"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="leadsNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ $homeUrl }}">{{ __('Home') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle px-3"
                            href="#"
                            id="leadsServicesDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            {{ __('Services') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2" aria-labelledby="leadsServicesDropdown" style="min-width: 16rem;">
                            @forelse ($leadNavItems as $item)
                                <li>
                                    <a class="dropdown-item rounded-2 py-2" href="{{ $item['url'] }}">
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item-text text-muted small">{{ __('Coming soon') }}</span></li>
                            @endforelse
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#how-it-works">{{ __('How it works') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#leads-footer-contact">{{ __('Contact') }}</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-leads-cta" href="#lead-form">{{ $ctaLabel }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
