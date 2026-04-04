@props([
    'page',
])

@php
    $title = $page['final_cta_title'] ?? __('Limited time — get your free consultation today');
    $text = $page['final_cta_text'] ?? __('Spots fill quickly each week. Submit your details now and lock in a no-obligation review with our team.');
    $btn = $page['final_cta_button'] ?? $page['hero_cta'] ?? __('Apply now');
@endphp

<section class="leads-section leads-final-cta text-center ls-animate">
    <div class="container py-4 py-md-5">
        @if (filled($title))
            <h2 class="h3 fw-bold mb-3">{{ $title }}</h2>
        @endif
        @if (filled($text))
            <p class="col-lg-8 mx-auto opacity-90 mb-4">{{ $text }}</p>
        @endif
        <a href="#lead-form" class="btn btn-light btn-lg leads-final-cta-btn">{{ $btn }}</a>
    </div>
</section>
