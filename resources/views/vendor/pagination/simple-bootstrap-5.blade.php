@if ($paginator->hasPages())
    <nav class="flex flex-wrap items-center justify-between gap-3 mt-4" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="text-sm text-gray-500">
            @php
                $first = $paginator->firstItem();
                $last = $paginator->lastItem();
                $total = $paginator->total();
            @endphp

            @if ($first !== null && $last !== null)
                {{ __('Showing') }} <span class="font-semibold text-gray-700">{{ $first }}</span>
                {{ __('to') }} <span class="font-semibold text-gray-700">{{ $last }}</span>
                {{ __('of') }} <span class="font-semibold text-gray-700">{{ $total }}</span>
            @else
                {{ __('Showing') }} <span class="font-semibold text-gray-700">{{ $paginator->count() }}</span>
                {{ __('of') }} <span class="font-semibold text-gray-700">{{ $total }}</span>
            @endif
        </div>

        <ul class="inline-flex items-center gap-1 overflow-x-auto max-w-full py-1">
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="px-3 py-1.5 text-gray-400 border border-gray-200 rounded-md cursor-not-allowed select-none">←</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       rel="prev"
                       aria-label="{{ __('pagination.previous') }}"
                       class="px-3 py-1.5 border border-gray-200 rounded-md hover:bg-gray-50 text-gray-700">
                        ←
                    </a>
                </li>
            @endif

            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       rel="next"
                       aria-label="{{ __('pagination.next') }}"
                       class="px-3 py-1.5 border border-gray-200 rounded-md hover:bg-gray-50 text-gray-700">
                        →
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="px-3 py-1.5 text-gray-400 border border-gray-200 rounded-md cursor-not-allowed select-none">→</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
