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
            'title' => __('Eligibility criteria'),
            'body' => __(
                'Most applications need stable income proof, valid ID, and age within partner limits. Requirements vary by product — we only ask for what your selected service typically needs next.'
            ),
        ],
        [
            'icon' => 'bi-folder2-open',
            'title' => __('Required documents'),
            'body' => __(
                'Keep digital copies of ID, address proof, income statements, and bank references ready. Uploads may be requested after your first call so partners can move faster.'
            ),
        ],
        [
            'icon' => 'bi-exclamation-triangle',
            'title' => __('Common mistakes to avoid'),
            'body' => __(
                'Incomplete contact details, mismatch between name on ID and application, and old income proofs are the top delay reasons. Double-check phone and email before you submit.'
            ),
        ],
        [
            'icon' => 'bi-lightning-charge',
            'title' => __('Tips to get approved faster'),
            'body' => __(
                'Apply with accurate numbers, respond quickly to verification calls, and avoid simultaneous duplicate applications across many lenders — it can impact scoring.'
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
                    {{ __('In-depth guide: What you should know before you apply') }}
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
