<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $u)
  <url>
    <loc>{{ $u['loc'] }}</loc>
    <lastmod>{{ $u['lastmod'] }}</lastmod>
@if (! empty($u['changefreq']))    <changefreq>{{ $u['changefreq'] }}</changefreq>
@endif
@if (! empty($u['priority']))    <priority>{{ $u['priority'] }}</priority>
@endif
  </url>
@endforeach
</urlset>
