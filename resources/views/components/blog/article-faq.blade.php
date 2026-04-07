{{--
    Reusable blog FAQ block: visible H2 + Q/A + optional JSON-LD (FAQPage) when $pushSchema is true.
    Store FAQs in blog_posts.faq_json as [["question"=>"","answer"=>""], ...] or legacy q/a keys.
--}}
@props([
    'items' => [],
    'pushSchema' => true,
])

@php
    $normalized = [];
    foreach ($items as $row) {
        if (! is_array($row)) {
            continue;
        }
        $q = $row['question'] ?? $row['q'] ?? null;
        $a = $row['answer'] ?? $row['a'] ?? null;
        if (is_string($q) && $q !== '' && is_string($a) && $a !== '') {
            $normalized[] = ['question' => $q, 'answer' => $a];
        }
    }
@endphp

@if ($normalized !== [])
    <section class="blog-article-faq mt-5 pt-4 border-top" aria-labelledby="blog-faq-title">
        <h2 id="blog-faq-title" class="h4 fw-bold mb-3">{{ __('Frequently asked questions') }}</h2>
        <dl class="mb-0">
            @foreach ($normalized as $pair)
                <dt class="fw-semibold mt-3">{{ $pair['question'] }}</dt>
                <dd class="text-muted mb-0 ms-0">{{ $pair['answer'] }}</dd>
            @endforeach
        </dl>
    </section>

    @if ($pushSchema)
        @push('json_ld')
            @php
                $faqEntities = [];
                foreach ($normalized as $pair) {
                    $faqEntities[] = [
                        '@type' => 'Question',
                        'name' => $pair['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $pair['answer'],
                        ],
                    ];
                }
                $faqLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $faqEntities,
                ];
            @endphp
            <script type="application/ld+json">
                {!! json_encode($faqLd, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
            </script>
        @endpush
    @endif
@endif
