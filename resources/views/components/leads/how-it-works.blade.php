@props([
    'page',
    'ctaLabel' => null,
])

@php
    $steps = $page['how_steps'] ?? [];
    if (! is_array($steps) || $steps === []) {
        $steps = [
            [
                'title' => __('Select your service'),
                'text' => __('Choose loan, insurance, investments, or another category in one tap at the top of the page.'),
            ],
            [
                'title' => __('Fill the short form'),
                'text' => __('Share your contact details securely — it takes under a minute on mobile.'),
            ],
            [
                'title' => __('Get the best offers'),
                'text' => __('Our team matches you with tailored options and follows up fast, with no obligation.'),
            ],
        ];
    }
    $icons = ['bi-ui-checks-grid', 'bi-pencil-square', 'bi-graph-up-arrow'];
@endphp

<section id="how-it-works" class="leads-section ls-animate" aria-labelledby="how-title">
    <div class="container">
        <div class="text-center mb-5">
            <h2 id="how-title" class="leads-section-title h3 mb-2">{{ __('How it works') }}</h2>
            <p class="text-muted mb-0 col-lg-8 mx-auto">{{ __('Three simple steps from browsing to speaking with an expert.') }}</p>
        </div>
        <div class="row g-4 g-lg-5 align-items-stretch justify-content-center">
            @foreach ($steps as $i => $step)
                <div class="col-md-4">
                    <div class="leads-how-step h-100 text-center p-4 rounded-4 border bg-white shadow-sm">
                        <div class="leads-how-icon-wrap mx-auto mb-3">
                            <i class="bi {{ $icons[$i] ?? 'bi-check2-circle' }} fs-2"></i>
                        </div>
                        <div class="leads-step-num leads-step-num--inline mb-3">{{ $i + 1 }}</div>
                        <h3 class="h6 fw-bold mb-2">{{ $step['title'] ?? '' }}</h3>
                        <p class="text-muted small mb-0">{{ $step['text'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="#lead-form" class="btn btn-leads-cta btn-lg px-4">{{ $ctaLabel ?? $page['hero_cta'] ?? __('Get free consultation') }}</a>
        </div>
    </div>
</section>
