@php
    // GA4 Measurement ID (preferred)
    $ga4 = trim((string) config('leads.ga4_measurement_id', ''));

    // Google Ads ID/label (optional)
    $googleAdsId = (string) (setting('google_ads_id') ?? '');
    $googleAdsId = trim($googleAdsId);
    $googleAdsLabel = (string) (setting('google_ads_conversion_label') ?? '');
    $googleAdsLabel = trim($googleAdsLabel);

    // If GA4 isn't set, we can still load gtag.js for Ads conversion-only.
    $primaryId = $ga4 !== '' ? $ga4 : $googleAdsId;

    $baseCurrency = (string) (config('currency.base', 'INR'));
    $baseCurrency = strtoupper(trim($baseCurrency ?: 'INR'));

    $consentKey = 'analytics_consent_v1';
@endphp

@if ($primaryId !== '')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $primaryId }}"></script>
    <script>
        (function () {
            'use strict';

            window.dataLayer = window.dataLayer || [];
            function gtag(){ dataLayer.push(arguments); }
            window.gtag = window.gtag || gtag;

            // Basic consent prep (default deny until user accepts).
            // This keeps the implementation production-safe for ads/analytics compliance.
            try {
                var stored = localStorage.getItem(@json($consentKey));
                var granted = stored === 'granted';
                gtag('consent', 'default', {
                    'analytics_storage': granted ? 'granted' : 'denied',
                    'ad_storage': granted ? 'granted' : 'denied',
                    'ad_user_data': granted ? 'granted' : 'denied',
                    'ad_personalization': granted ? 'granted' : 'denied'
                });
            } catch (e) {}

            gtag('js', new Date());

            @if ($ga4 !== '')
                gtag('config', @json($ga4));
            @endif

            @if ($googleAdsId !== '' && $googleAdsId !== $ga4)
                gtag('config', @json($googleAdsId));
            @elseif ($googleAdsId !== '' && $ga4 === '')
                gtag('config', @json($googleAdsId));
            @endif

            // Standardized event wrapper
            window.trackEvent = function (name, params) {
                if (typeof window.gtag !== 'function' || !name) return;
                try {
                    window.gtag('event', String(name), params || {});
                } catch (e) {}
            };

            // Backward-compatible helpers used across blades
            window.trackGoogleConversion = function () {
                @if ($googleAdsId !== '' && $googleAdsLabel !== '')
                if (typeof window.gtag !== 'function') return;
                window.gtag('event', 'conversion', { 'send_to': @json($googleAdsId . '/' . $googleAdsLabel) });
                @endif
            };

            window.trackLeadGenerateEvent = function (extra) {
                @if ($ga4 !== '')
                var payload = extra && typeof extra === 'object' ? extra : {};
                payload.currency = payload.currency || @json($baseCurrency);
                payload.value = payload.value || 1;
                window.trackEvent('generate_lead', payload);
                @endif
            };

            // Standard events requested
            window.trackCtaClick = function (location, label, pageType) {
                window.trackEvent('cta_click', {
                    location: location || undefined,
                    label: label || undefined,
                    page_type: pageType || undefined
                });
            };
            window.trackBeginCheckout = function (context) {
                window.trackEvent('begin_checkout', context || {});
            };
            window.trackPurchase = function (context) {
                window.trackEvent('purchase', context || {});
            };
            window.trackWalletTopupInitiated = function (context) {
                window.trackEvent('wallet_topup_initiated', context || {});
            };
            window.trackWalletTopupSuccess = function (context) {
                window.trackEvent('wallet_topup_success', context || {});
            };
        })();
    </script>

    {{-- Basic consent notice (lightweight, no layout changes). --}}
    <style>
        .consent-banner{position:fixed;left:12px;right:12px;bottom:12px;z-index:2500;max-width:920px;margin:0 auto;background:#0f172a;color:#e2e8f0;border:1px solid rgba(148,163,184,.25);border-radius:14px;padding:12px 14px;box-shadow:0 10px 30px rgba(0,0,0,.25);display:none}
        .consent-banner a{color:#93c5fd;text-decoration:underline}
        .consent-banner__row{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between}
        .consent-banner__actions{display:flex;gap:8px;align-items:center}
        .consent-btn{border-radius:999px;padding:8px 12px;font-size:13px;font-weight:700;border:1px solid rgba(148,163,184,.35);background:transparent;color:#e2e8f0}
        .consent-btn--primary{background:#2563eb;border-color:#2563eb;color:white}
    </style>
    <div class="consent-banner" id="consentBanner" role="dialog" aria-live="polite" aria-label="Cookie consent">
        <div class="consent-banner__row">
            <div style="font-size:13px;line-height:1.35;">
                {{ __('We use cookies for analytics and ads to improve your experience.') }}
                <a href="{{ route('privacy-policy') }}">{{ __('Learn more') }}</a>.
            </div>
            <div class="consent-banner__actions">
                <button type="button" class="consent-btn" id="consentRejectBtn">{{ __('Reject') }}</button>
                <button type="button" class="consent-btn consent-btn--primary" id="consentAcceptBtn">{{ __('Accept') }}</button>
            </div>
        </div>
    </div>
    <script>
        (function () {
            'use strict';
            var key = @json($consentKey);
            var banner = document.getElementById('consentBanner');
            if (!banner) return;
            try {
                var stored = localStorage.getItem(key);
                if (stored !== 'granted' && stored !== 'denied') {
                    banner.style.display = 'block';
                }
            } catch (e) {
                banner.style.display = 'block';
            }

            function setConsent(granted) {
                try { localStorage.setItem(key, granted ? 'granted' : 'denied'); } catch (e) {}
                if (typeof window.gtag === 'function') {
                    window.gtag('consent', 'update', {
                        'analytics_storage': granted ? 'granted' : 'denied',
                        'ad_storage': granted ? 'granted' : 'denied',
                        'ad_user_data': granted ? 'granted' : 'denied',
                        'ad_personalization': granted ? 'granted' : 'denied'
                    });
                }
                banner.style.display = 'none';
            }

            var accept = document.getElementById('consentAcceptBtn');
            var reject = document.getElementById('consentRejectBtn');
            if (accept) accept.addEventListener('click', function () { setConsent(true); });
            if (reject) reject.addEventListener('click', function () { setConsent(false); });
        })();
    </script>
@endif

