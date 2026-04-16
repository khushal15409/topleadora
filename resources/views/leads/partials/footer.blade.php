@php
    $app = config('app.name', 'App');
    $year = now()->year;
    $supportEmail = (string) (config('branding.support_email') ?? '');
    $supportEmail = trim($supportEmail);
@endphp

<footer class="leads-footer mt-auto">
    <div class="container-xl py-5">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4">
                <div class="mb-3">
                    <img
                        src="{{ asset('front/images/logo.png') }}"
                        alt="{{ $app }}"
                        class="leads-footer-logo"
                        loading="lazy"
                        decoding="async"
                        width="400"
                        height="96"
                    >
                </div>
                <p class="small mb-0 lh-lg" style="max-width: 26rem;">
                    {{ __('We connect serious buyers with vetted partners — loans, insurance, property, solar, education, legal, auto, and more. Less noise, clearer next steps.') }}
                </p>
            </div>
            <div class="col-6 col-lg-4">
                <div class="footer-heading">{{ __('Services') }}</div>
                <ul class="list-unstyled small mb-0">
                    @foreach ($leadNavItems->take(36) as $item)
                        <li class="mb-2">
                            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-4" id="leads-footer-contact">
                <div class="footer-heading">{{ __('Contact') }}</div>
                @if ($supportEmail !== '')
                    <p class="small mb-2">{{ __('Email') }}: <a href="mailto:{{ e($supportEmail) }}">{{ $supportEmail }}</a></p>
                @else
                    <p class="small mb-2">{{ __('Email') }}: <a href="{{ route('contact') }}">{{ __('Use our contact form') }}</a></p>
                @endif
                <p class="small mb-3">{{ __('Hours') }}: {{ __('Mon–Sat, 9am–7pm IST') }}</p>
                {{-- Social links intentionally hidden until real URLs exist (avoid "#" placeholders). --}}
            </div>
        </div>

        <hr class="border-secondary border-opacity-25 my-4">

        <div class="small leads-footer-disclaimer-wrap text-secondary-emphasis" style="--bs-secondary-color: #94a3b8;">
            <p class="mb-2 fw-semibold text-uppercase leads-footer-disclaimer-heading" style="font-size: 0.68rem; letter-spacing: 0.08em;">{{ __('Important disclaimer') }}</p>
            <p class="mb-2 lh-lg leads-footer-disclaimer-body">
                {{ __('Results vary. Offers, approvals, licencing, and eligibility depend on third parties and local regulations. Nothing on this site is a promise of financing, returns, legal outcomes, or guaranteed savings.') }}
                {{ __('For investments and regulated products (including securities, credit, and insurance), read all documents and consult licensed professionals in your country before you proceed.') }}
            </p>
            <div class="leads-footer-disclaimer-body">
                @yield('footer_niche_disclaimer')
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 pt-3 small">
            <span>© {{ $year }} {{ $app }}. {{ __('All rights reserved.') }}</span>
            <div class="d-flex flex-wrap align-items-center gap-3 justify-content-center">
                <a href="{{ route('privacy-policy') }}" class="link-light text-decoration-none">{{ __('Privacy') }}</a>
                <a href="{{ route('terms') }}" class="link-light text-decoration-none">{{ __('Terms') }}</a>
                <a href="{{ route('refund-policy') }}" class="link-light text-decoration-none">{{ __('Refunds') }}</a>
                <a href="#lead-form" class="btn btn-sm btn-outline-light border-opacity-25">{{ __('Get free leads') }}</a>
            </div>
        </div>
    </div>
</footer>
