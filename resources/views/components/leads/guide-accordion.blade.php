@props([
    'slug',
    'showShareRisk' => false,
    'seoBody' => null,
])

@php
    $accordionId = 'leads-guide-'.$slug;
    $items = [
        [
            'icon' => 'bi-person-check',
            'title' => __('Who this is for'),
            'body' => __(
                'Best for teams in India that can respond quickly to inbound leads and run a repeatable qualification process for real estate, insurance, agencies, or local services.'
            ),
        ],
        [
            'icon' => 'bi-folder2-open',
            'title' => __('What details you get in each lead'),
            'body' => __(
                'Each lead usually includes name, mobile number, service interest, city context, and optional notes submitted by the enquiry.'
            ),
        ],
        [
            'icon' => 'bi-exclamation-triangle',
            'title' => __('How leads are generated'),
            'body' => __(
                'Leads are generated through service + city landing pages, validated form submissions, and routing rules designed for faster first contact by your sales team.'
            ),
        ],
        [
            'icon' => 'bi-lightning-charge',
            'title' => __('Performance benchmarks'),
            'body' => __(
                'Most teams see better outcomes when first contact happens within 10-30 minutes and at least two follow-up attempts are completed on day one.'
            ),
        ],
    ];
    if ($showShareRisk) {
        $items[] = [
            'icon' => 'bi-shield-exclamation',
            'title' => __('Share market risk disclaimer'),
            'body' => __(
                'Securities investments are subject to market risks. Past performance does not guarantee future results. Read all scheme-related documents carefully and consider professional advice before investing.'
            ),
        ];
    }
@endphp

<section class="leads-section leads-guide-section ls-animate" aria-labelledby="{{ $accordionId }}-title">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <h2 id="{{ $accordionId }}-title" class="leads-section-title h3 mb-4 text-center">
                    {{ __('Lead quality guide for India campaigns') }}
                </h2>
                <div class="accordion leads-guide-accordion" id="{{ $accordionId }}">
                    @foreach ($items as $i => $item)
                        @php $collapseId = $accordionId.'-item-'.$i; @endphp
                        <div class="accordion-item leads-guide-item border-0 mb-3 rounded-4 overflow-hidden shadow-sm">
                            <h3 class="accordion-header m-0">
                                <button
                                    class="accordion-button leads-guide-toggle @if ($i > 0) collapsed @endif"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                    aria-controls="{{ $collapseId }}"
                                >
                                    <span class="leads-guide-icon" aria-hidden="true">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>
                                    <span class="fw-semibold">{{ $item['title'] }}</span>
                                </button>
                            </h3>
                            <div
                                id="{{ $collapseId }}"
                                class="accordion-collapse collapse @if ($i === 0) show @endif"
                            >
                                <div class="accordion-body text-body-secondary small lh-lg pt-0">
                                    {{ $item['body'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if (filled($seoBody))
                        @php $seoIdx = count($items); $collapseId = $accordionId.'-item-seo'; @endphp
                        <div class="accordion-item leads-guide-item border-0 mb-3 rounded-4 overflow-hidden shadow-sm">
                            <h3 class="accordion-header m-0">
                                <button
                                    class="accordion-button leads-guide-toggle collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="false"
                                    aria-controls="{{ $collapseId }}"
                                >
                                    <span class="leads-guide-icon" aria-hidden="true">
                                        <i class="bi bi-journal-text"></i>
                                    </span>
                                    <span class="fw-semibold">{{ __('Full detailed guide') }}</span>
                                </button>
                            </h3>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse">
                                <div class="accordion-body pt-0">
                                    <div class="leads-seo-prose text-body-secondary small">
                                        {!! $seoBody !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
