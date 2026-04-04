@props([
    'page',
    'slug',
])

<section class="leads-section leads-section--muted leads-faq ls-animate" aria-labelledby="faq-title">
    <div class="container">
        <div class="text-center mb-5">
            <h2 id="faq-title" class="leads-section-title h3 mb-2">{{ __('Frequently asked questions') }}</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion accordion-flush" id="faqAccordion-{{ $slug }}">
                    @foreach ($page['faqs'] ?? [] as $i => $faq)
                        <div class="accordion-item border rounded-3 mb-2 overflow-hidden bg-white">
                            <h3 class="accordion-header m-0">
                                <button
                                    class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq-{{ $slug }}-{{ $i }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                    aria-controls="faq-{{ $slug }}-{{ $i }}"
                                >
                                    {{ $faq['q'] ?? '' }}
                                </button>
                            </h3>
                            <div
                                id="faq-{{ $slug }}-{{ $i }}"
                                class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                data-bs-parent="#faqAccordion-{{ $slug }}"
                            >
                                <div class="accordion-body text-muted small">
                                    {{ $faq['a'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
