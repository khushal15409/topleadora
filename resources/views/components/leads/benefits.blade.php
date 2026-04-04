@props([
    'page',
])

<section class="leads-section ls-animate" aria-labelledby="benefits-title">
    <div class="container">
        <div class="text-center mb-5">
            <h2 id="benefits-title" class="leads-section-title h3 mb-2">{{ __('What you get') }}</h2>
            <p class="text-muted mb-0 col-lg-8 mx-auto">{{ __('Practical benefits — built for busy people who want results, not jargon.') }}</p>
        </div>
        <div class="row g-4">
            @foreach ($page['benefits'] ?? [] as $b)
                <div class="col-md-6 col-xl-4">
                    <div class="leads-card h-100 p-4">
                        <div class="leads-benefit-icon mb-3">
                            <i class="bi {{ $b['icon'] ?? 'bi-check2-circle' }}" aria-hidden="true"></i>
                        </div>
                        <h3 class="h6 fw-bold mb-2">{{ $b['title'] ?? '' }}</h3>
                        <p class="text-muted small mb-0">{{ $b['text'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
