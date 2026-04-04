@props([
    'services',
    'defaultServiceId' => null,
])

@if ($services !== null && $services->isNotEmpty())
    <div class="leads-service-pills-wrap" role="group" aria-label="{{ __('Choose a service') }}">
        <p class="leads-service-pills-label mb-2">{{ __('Select your service') }}</p>
        <div class="leads-service-pills">
            @foreach ($services as $svc)
                <button
                    type="button"
                    class="leads-service-pill @if ((int) $defaultServiceId === (int) $svc->id) is-active @endif"
                    data-service-id="{{ $svc->id }}"
                    data-service-name="{{ $svc->name }}"
                    aria-pressed="{{ (int) $defaultServiceId === (int) $svc->id ? 'true' : 'false' }}"
                >
                    {{ $svc->name }}
                </button>
            @endforeach
        </div>
    </div>
@endif
