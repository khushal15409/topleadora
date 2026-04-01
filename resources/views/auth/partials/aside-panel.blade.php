{{--
  Left column for login/register: gradient background, glass logo bar, copy, framed illustration.
  Expects: $asideTitle, $asideText, $asideBullets (array of ['icon' => 'bi bi-...', 'label' => '...']), $asideImage (path under public, e.g. front/images/...)
--}}
<aside class="auth-aside d-none d-lg-flex" aria-label="Product highlights">
    <div class="auth-aside__bg" aria-hidden="true"></div>
    <div class="auth-aside__mesh" aria-hidden="true"></div>

    <div class="auth-aside__inner">
        <header class="auth-aside__header">
            <a href="{{ url('/') }}" class="auth-aside__brand">
                <img src="{{ asset('front/images/logo.png') }}" alt="{{ config('app.name') }}" width="400" height="96" loading="eager">
            </a>
            <h2 class="auth-aside__title">{{ $asideTitle }}</h2>
            <p class="auth-aside__text">{{ $asideText }}</p>
            <ul class="auth-aside__bullets">
                @foreach ($asideBullets as $row)
                    <li>
                        <span class="auth-aside__bullet-icon" aria-hidden="true">
                            <i class="{{ $row['icon'] }}"></i>
                        </span>
                        <span>{{ $row['label'] }}</span>
                    </li>
                @endforeach
            </ul>
        </header>

        <div class="auth-aside__visual">
            <figure class="auth-aside__figure">
                <img
                    class="auth-aside__img"
                    src="{{ asset($asideImage) }}"
                    alt=""
                    width="420"
                    height="320"
                    loading="lazy"
                    decoding="async"
                >
            </figure>
        </div>
    </div>
</aside>
