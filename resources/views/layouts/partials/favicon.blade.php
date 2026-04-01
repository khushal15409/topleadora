@php
    $faviconPath = config('branding.favicon', 'front/images/landify/favicon.png');
    $faviconUrl = asset($faviconPath);
@endphp
<link rel="icon" href="{{ $faviconUrl }}" type="image/png">
<link rel="shortcut icon" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}">
