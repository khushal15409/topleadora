@props([
    'page',
])

@php
    $testimonials = $page['testimonials'] ?? [];
    if (! is_array($testimonials)) {
        $testimonials = [];
    }
    if ($testimonials === []) {
        $testimonials = [
            [
                'name' => __('Priya S.'),
                'role' => __('Business owner'),
                'text' => __('We use this for real estate leads in Pune. Team callbacks became more consistent because lead details arrive in a structured format.'),
            ],
            [
                'name' => __('Rahul M.'),
                'role' => __('IT consultant'),
                'text' => __('As an insurance advisor, I get cleaner enquiry context and can prioritize serious prospects faster.'),
            ],
            [
                'name' => __('Neha K.'),
                'role' => __('Freelancer'),
                'text' => __('Our agency uses these lead workflows for India campaigns. Reporting is clearer and follow-up handoff is easier.'),
            ],
        ];
    }
@endphp

<section class="leads-section leads-section--muted border-top border-bottom ls-animate">
    <div class="container">
        <div class="text-center mb-5">
            <p class="leads-trust-stat fw-bold text-dark h5 mb-2">{{ __('Used by growing businesses across India') }}</p>
            <div class="leads-stars fs-5 mb-1" aria-label="{{ __('5 out of 5 stars') }}" aria-hidden="true">★★★★★</div>
            <p class="text-muted small mb-0">{{ __('Known for practical onboarding, clear lead context, and responsive support') }}</p>
        </div>
        <div class="row g-4 align-items-start mb-5">
            <div class="col-md-5">
                <x-leads.responsive-image
                    :src="$page['section_trust_image'] ?? ''"
                    :fallback="$page['trust_image_fallback'] ?? null"
                    class="rounded-4 shadow-sm w-100"
                    :alt="__('Team collaboration')"
                />
            </div>
            <div class="col-md-7">
                <h2 class="leads-section-title h3 mb-3">{{ __('Why people trust this process') }}</h2>
                <p class="text-muted lead mb-4">
                    {{ __('We connect you with vetted partners, plain-language guidance, and a team that respects your time.') }}
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if (! empty($page['trust_badge']))
                        <span class="badge rounded-pill text-bg-light border px-3 py-2">{{ $page['trust_badge'] }}</span>
                    @endif
                    <span class="badge rounded-pill text-bg-light border px-3 py-2">{{ __('Encrypted submissions') }}</span>
                    <span class="badge rounded-pill text-bg-light border px-3 py-2">{{ __('No spam') }}</span>
                </div>
                @if (! empty($page['rating_label']))
                    <div class="mt-3 leads-stars" aria-hidden="true">★★★★★ <span class="text-body-secondary small ms-1">{{ $page['rating_label'] }}</span></div>
                @endif
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach ($testimonials as $t)
                <div class="col-md-4">
                    <div class="leads-card h-100 p-4">
                        <div class="leads-stars small mb-2" aria-hidden="true">★★★★★</div>
                        <p class="mb-4 small text-body-secondary">“{{ $t['text'] ?? '' }}”</p>
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
