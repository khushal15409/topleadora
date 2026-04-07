<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\LandingPage;
use Illuminate\Support\Str;

/**
 * Central SEO helpers: unique titles, descriptions, canonical URLs, OG images.
 */
class SeoMeta
{
    public static function defaultOgImageUrl(): string
    {
        $path = config('seo.default_og_image', 'front/images/landify/illustration/illustration-15.webp');

        return asset($path);
    }

    /**
     * Resolve OG/Twitter image: explicit URL, post image, or global fallback.
     */
    public static function ogImageForBlog(?BlogPost $post): string
    {
        if ($post !== null && filled($post->image)) {
            return asset($post->image);
        }

        return self::defaultOgImageUrl();
    }

    /**
     * Lead landing: use page og_image from array (already resolved) or hero or default.
     *
     * @param  array<string, mixed>  $page
     */
    public static function ogImageForLeadPage(array $page): string
    {
        foreach (['og_image', 'hero_image', 'section_trust_image'] as $key) {
            $v = $page[$key] ?? null;
            if (is_string($v) && $v !== '') {
                return Str::startsWith($v, ['http://', 'https://']) ? $v : asset($v);
            }
        }

        return self::defaultOgImageUrl();
    }

    /**
     * Canonical absolute URL for a named route.
     */
    public static function canonical(string $route, mixed $parameter = null): string
    {
        return $parameter !== null
            ? route($route, $parameter, true)
            : route($route, absolute: true);
    }

    public static function formatDescription(?string $text, ?int $max = null): string
    {
        $max ??= (int) config('seo.description_max_length', 160);
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags((string) $text)) ?? '');

        return Str::limit($plain, $max, '');
    }

    /**
     * Auto meta when admin leaves title/description empty (programmatic / city landings).
     *
     * @return array{title: string, description: string}
     */
    public static function fallbackForMarketingLanding(LandingPage $landing): array
    {
        $service = $landing->service?->name ?? __('Financial service');
        $country = $landing->country?->name ?? 'India';
        $city = trim((string) $landing->city_label);

        if ($city !== '') {
            $title = __(':service in :city, :country | Apply online', [
                'service' => $service,
                'city' => $city,
                'country' => $country,
            ]);
            $description = __('Apply for :service in :city. Free consultation, quick response. Secure form — we route you to licensed partners in :country.', [
                'service' => $service,
                'city' => $city,
                'country' => $country,
            ]);
        } else {
            $title = __(':service in :country | Apply online', [
                'service' => $service,
                'country' => $country,
            ]);
            $description = __('Apply for :service online. Free consultation and fast callback. Serving customers across :country.', [
                'service' => $service,
                'country' => $country,
            ]);
        }

        return [
            'title' => Str::limit(trim($title), 72),
            'description' => self::formatDescription($description),
        ];
    }

    /**
     * Blog listing / generic fallback when meta fields are empty.
     *
     * @return array{title: string, description: string}
     */
    public static function fallbackForBlogPost(BlogPost $post): array
    {
        $suffix = (string) config('seo.title_suffix', '');
        $title = filled($post->meta_title)
            ? $post->meta_title
            : ($post->title.($suffix !== '' && ! str_contains($post->title, trim($suffix, ' |')) ? $suffix : ''));

        $desc = $post->meta_description
            ?? $post->excerpt
            ?? self::formatDescription($post->body);

        return [
            'title' => Str::limit(trim($title), 72),
            'description' => $desc !== '' ? Str::limit(trim($desc), (int) config('seo.description_max_length', 160)) : self::formatDescription($post->body),
        ];
    }
}
