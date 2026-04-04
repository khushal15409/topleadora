@php
    $ga4 = trim((string) config('leads.ga4_measurement_id', ''));
    $googleAdsId = (string) (setting('google_ads_id') ?? '');
    $googleAdsLabel = (string) (setting('google_ads_conversion_label') ?? '');
    $primaryId = $ga4 !== '' ? $ga4 : $googleAdsId;
@endphp

@if ($primaryId !== '')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $primaryId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        @if ($ga4 !== '')
            gtag('config', '{{ $ga4 }}');
        @endif
        @if ($googleAdsId !== '' && $googleAdsId !== $ga4)
            gtag('config', '{{ $googleAdsId }}');
        @elseif ($googleAdsId !== '' && $ga4 === '')
            gtag('config', '{{ $googleAdsId }}');
        @endif
    </script>
@endif

<script>
    function trackGoogleConversion() {
        @if ($googleAdsId !== '' && $googleAdsLabel !== '')
        if (typeof gtag === 'function') {
            gtag('event', 'conversion', {
                'send_to': '{{ $googleAdsId }}/{{ $googleAdsLabel }}'
            });
        }
        @endif
    }

    function trackLeadGenerateEvent() {
        @if ($ga4 !== '')
        if (typeof gtag === 'function') {
            gtag('event', 'generate_lead', {
                currency: 'USD',
                value: 1
            });
        }
        @endif
    }
</script>
