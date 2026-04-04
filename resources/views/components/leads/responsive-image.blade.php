@props([
    'src',
    'alt' => '',
    'class' => '',
    'sizes' => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 720px',
    'width' => 1200,
    'height' => 800,
    'lazy' => true,
    'fallback' => null,
])

@php
    $primary = $src !== null && $src !== '' ? leadPublicImageUrl((string) $src) : '';
    if ($primary === '') {
        $primary = leadImageFallbackUrl();
    }
    $fbRaw = $fallback !== null && $fallback !== '' ? leadPublicImageUrl((string) $fallback) : leadImageFallbackUrl();
    $fb = $fbRaw !== '' ? $fbRaw : leadImageFallbackUrl();
    $srcset = leadResponsiveSrcset($primary);
    $fetch = $lazy ? 'lazy' : 'high';
    $decoding = 'async';
    $localPh = leadLocalPlaceholderImageUrl();
@endphp

<img
    src="{{ $primary }}"
    alt="{{ $alt }}"
    class="img-fluid {{ $class }}"
    width="{{ $width }}"
    height="{{ $height }}"
    sizes="{{ $sizes }}"
    @if ($srcset !== '')
        srcset="{{ $srcset }}"
    @endif
    loading="{{ $fetch }}"
    decoding="{{ $decoding }}"
    onerror="(function(el){el.removeAttribute('srcset');var s=['{{ e($fb) }}','{{ e($localPh) }}'];var i=parseInt(el.dataset.li||'0',10);while(i<s.length&&(!s[i]||s[i]===el.src))i++;if(i>=s.length){el.onerror=null;return;}el.dataset.li=String(i+1);el.src=s[i];})(this)"
>
