@props([
    'page',
])

<section class="leads-section ls-animate" aria-labelledby="testimonials-title">
    <div class="container">
        <div class="text-center mb-5">
            <h2 id="testimonials-title" class="leads-section-title h3 mb-2">{{ __('Trusted by people like you') }}</h2>
            <p class="text-muted mb-0">{{ __('Real experiences vary by service and city. We focus on clear guidance, fast response, and respectful communication.') }}</p>
        </div>
        <div class="row g-4">
            @foreach ($page['testimonials'] ?? [] as $t)
                <div class="col-md-4">
                    <div class="leads-card h-100 p-4">
                        <div class="leads-stars small mb-2" aria-hidden="true">★★★★★</div>
                        <p class="mb-4 small">“{{ $t['text'] ?? '' }}”</p>
                        <div class="d-flex align-items-center gap-2 mt-auto">
                            <span class="leads-testimonial-avatar">{{ \Illuminate\Support\Str::substr((string) ($t['name'] ?? '?'), 0, 1) }}</span>
                            <div>
                                <div class="fw-semibold small mb-0">{{ $t['name'] ?? '' }}</div>
                                <div class="text-muted small">{{ $t['role'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
