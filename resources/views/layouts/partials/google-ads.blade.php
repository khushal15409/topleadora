@php
    $googleAdsId = (string) (setting('google_ads_id') ?? '');
    $googleAdsLabel = (string) (setting('google_ads_conversion_label') ?? '');
@endphp

@if ($googleAdsId !== '')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAdsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', '{{ $googleAdsId }}');
    </script>
@endif

<script>
    // Safe helper: no errors when settings missing.
    function trackGoogleConversion() {
        @if ($googleAdsId !== '' && $googleAdsLabel !== '')
        if (typeof gtag === 'function') {
            gtag('event', 'conversion', {
                'send_to': '{{ $googleAdsId }}/{{ $googleAdsLabel }}'
            });
        }
        @endif
    }
</script>

