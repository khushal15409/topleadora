@props([
    'page',
    'slug',
])

@php
    $heroImg = ! empty($page['hero_image']) ? leadPublicImageUrl((string) $page['hero_image']) : '';
    if ($heroImg === '') {
        $heroImg = (string) config('leads.hero_default_image_url');
    }
    if ($heroImg === '') {
        $heroImg = leadImageFallbackUrl();
    }
    $heroSrcset = leadResponsiveSrcset($heroImg);
    $heroFallbackPrimary = ! empty($page['hero_image_fallback']) ? leadPublicImageUrl((string) $page['hero_image_fallback']) : '';
    if ($heroFallbackPrimary === '' || $heroFallbackPrimary === $heroImg) {
        $heroFallbackPrimary = leadImageFallbackUrl();
    }
    $heroFallback = $heroFallbackPrimary;
    $heroFallbackFinal = leadImageFallbackUrl();
    $heroFallbackLocal = leadLocalPlaceholderImageUrl();
@endphp

<section class="leads-hero" aria-labelledby="leads-hero-title">
    <div class="leads-hero__bg" aria-hidden="true">
        <img
            class="leads-hero__bg-img"
            src="{{ $heroImg }}"
            alt=""
            width="1920"
            height="1080"
            decoding="async"
            fetchpriority="high"
            loading="eager"
            sizes="100vw"
            @if ($heroSrcset !== '')
                srcset="{{ $heroSrcset }}"
            @endif
            onerror="(function(el){el.removeAttribute('srcset');var s=['{{ e($heroFallback) }}','{{ e($heroFallbackFinal) }}','{{ e($heroFallbackLocal) }}'];var i=parseInt(el.dataset.leadsHfb||'0',10);while(i<s.length&&(!s[i]||s[i]===el.src))i++;if(i>=s.length){el.onerror=null;return;}el.dataset.leadsHfb=String(i+1);el.src=s[i];})(this)"
        >
    </div>
    <div class="leads-hero__overlay" aria-hidden="true"></div>
    <div class="container leads-hero__inner">
        @isset($top)
            <div class="row">
                <div class="col-12 mb-4 mb-lg-5">
                    {{ $top }}
                </div>
            </div>
        @endisset
        <div class="row g-4 g-xl-5 align-items-start align-items-lg-center">
            <div class="col-lg-6 ls-animate">
                @if (! empty($page['trust_badge']))
                    <p class="small text-uppercase fw-semibold mb-2 opacity-75">{{ $page['trust_badge'] }}</p>
                @endif
                <h1 id="leads-hero-title" class="leads-hero__title mb-3">{{ $page['hero_headline'] }}</h1>
                @if (! empty($page['hero_subheadline']))
                    <p class="leads-hero__sub mb-4">{{ $page['hero_subheadline'] }}</p>
                @endif
                <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
                    <a href="#lead-form" class="btn btn-light leads-hero__cta-outline">{{ $page['hero_cta'] ?? __('Apply now') }}</a>
                    @if (! empty($page['rating_label']))
                        <div>
                            <div class="leads-stars small" aria-hidden="true">★★★★★</div>
                            <div class="small opacity-75">{{ $page['rating_label'] }}</div>
                        </div>
                    @endif
                </div>
                <p class="small opacity-75 mb-0 d-none d-md-block">{{ __('Free consultation · No obligation · Quick response') }}</p>
            </div>
            <div class="col-lg-6 ls-animate">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
