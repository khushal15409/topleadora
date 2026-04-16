<?php

namespace App\Support;

use App\Models\Service;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Resolves /leads/{slug} when slug = {service_slug}-{city_slug} (single segment).
 * No extra route registration — avoids colliding with existing /leads/{slug}.
 */
class ProgrammaticLeadResolver
{
    public static function enabled(): bool
    {
        return (bool) config('programmatic_seo.enabled', true);
    }

    /**
     * @return list<string>
     */
    public static function citySlugs(): array
    {
        return collect(config('programmatic_seo.india_cities', []))
            ->pluck('slug')
            ->filter()
            ->map(fn (string $s) => Str::lower($s))
            ->unique()
            ->values()
            ->all();
    }

    public static function cityLabel(string $citySlug): ?string
    {
        $slug = Str::lower($citySlug);
        foreach (config('programmatic_seo.india_cities', []) as $row) {
            if (! is_array($row)) {
                continue;
            }
            if (Str::lower((string) ($row['slug'] ?? '')) === $slug) {
                return (string) ($row['label'] ?? '');
            }
        }

        return null;
    }

    /**
     * @return array{service: Service, city_slug: string, city_label: string}|null
     */
    public static function resolve(string $slug): ?array
    {
        if (! self::enabled() || ! Schema::hasTable('services')) {
            return null;
        }

        $slug = Str::lower(trim($slug));
        if ($slug === '') {
            return null;
        }

        $citySlugs = self::citySlugs();
        if ($citySlugs === []) {
            return null;
        }

        $services = Service::query()->activeOrdered()->get();
        $ordered = $services->sortByDesc(fn (Service $s) => strlen((string) $s->slug));

        foreach ($ordered as $service) {
            $prefix = $service->slug.'-';
            if ($prefix === '-' || ! str_starts_with($slug, $prefix)) {
                continue;
            }

            $citySlug = substr($slug, strlen($prefix));
            if ($citySlug === '' || ! in_array($citySlug, $citySlugs, true)) {
                continue;
            }

            $cityLabel = self::cityLabel($citySlug);
            if ($cityLabel === null || $cityLabel === '') {
                continue;
            }

            return [
                'service' => $service,
                'city_slug' => $citySlug,
                'city_label' => $cityLabel,
            ];
        }

        return null;
    }

    /**
     * Build a public $page array (same shape as LandingPage::toPublicPageArray consumers).
     *
     * @return array<string, mixed>
     */
    public static function buildPublicPage(string $fullSlug, Service $service, string $cityLabel): array
    {
        $defaults = is_array($service->default_content_json) ? $service->default_content_json : [];
        $base = array_replace_recursive(MarketingLandingDefaults::shell(), $defaults);

        $titleTemplate = (string) config('programmatic_seo.title_pattern');
        $descTemplate = (string) config('programmatic_seo.description_pattern');

        $rawTitle = __($titleTemplate, [
            'service' => $service->name,
            'city' => $cityLabel,
        ]);
        $rawDesc = __($descTemplate, [
            'service' => $service->name,
            'city' => $cityLabel,
        ]);

        $base['meta_title'] = Str::limit(trim(strip_tags($service->name.' Leads in '.$cityLabel.' | India')), 60);
        $base['meta_description'] = SeoMeta::formatDescription($rawDesc);
        $base['meta_keywords'] = Str::limit(strtolower($service->name).', '.$cityLabel.', buy leads in india, lead generation services india', 512);
        $base['robots_meta'] = 'index,follow';

        $base['hero_headline'] = __('Get Verified :service Leads in :city', [
            'service' => $service->name,
            'city' => $cityLabel,
        ]);
        $base['hero_subheadline'] = __('Need :service demand in :city? Submit one short form to get qualified lead support for India-focused campaigns and fast follow-up workflows.', [
            'city' => $cityLabel,
            'service' => strtolower($service->name),
        ]);
        $base['hero_cta'] = (string) config('programmatic_seo.hero_cta', __('Apply now in 2 minutes'));

        $base['niche_label'] = $service->name;
        $base['niche_slug'] = $service->slug;
        $base['location_label'] = $cityLabel.', India';
        $base['landing_slug'] = $fullSlug;

        $base['seo_body'] = self::uniqueSeoBodyHtml($service, $cityLabel);

        $base['faqs'] = self::buildFaqs($service, $cityLabel);

        foreach ([
            'hero_image',
            'hero_image_fallback',
            'og_image',
            'section_trust_image',
            'trust_image_fallback',
            'how_image',
            'how_image_fallback',
        ] as $imgKey) {
            if (! empty($base[$imgKey]) && is_string($base[$imgKey])) {
                $base[$imgKey] = leadPublicImageUrl($base[$imgKey]);
            }
        }

        return $base;
    }

    /**
     * @return list<array{q: string, a: string}>
     */
    private static function buildFaqs(Service $service, string $cityLabel): array
    {
        return [
            [
                'q' => __('Are these :service leads location-specific for :city?', ['service' => strtolower($service->name), 'city' => $cityLabel]),
                'a' => __('Yes. This page targets :city demand so your team receives India-ready enquiries with city context for faster qualification.', ['city' => $cityLabel]),
            ],
            [
                'q' => __('What details are included in each lead?', []),
                'a' => __('Typical details include name, mobile number, service interest, and city-level intent context shared through a secure workflow.', []),
            ],
            [
                'q' => __('How quickly should we contact new leads?', []),
                'a' => __('Best results usually come when teams call within 10-30 minutes during business hours and run at least 2-3 structured follow-ups.', []),
            ],
        ];
    }

    private static function uniqueSeoBodyHtml(Service $service, string $cityLabel): string
    {
        return '<section class="programmatic-seo-body">'
            .'<h2>'.e(__('Who this is for in :city', ['city' => $cityLabel])).'</h2>'
            .'<p>'.e(__('Best for real estate teams, insurance advisors, agencies, and local businesses that can contact new enquiries quickly and maintain a follow-up process.', [])).'</p>'
            .'<h2>'.e(__('How lead generation works', [])).'</h2>'
            .'<p>'.e(__('Step 1: City and service demand is captured through targeted pages. Step 2: Enquiries are validated and routed. Step 3: Your team follows up on WhatsApp or calls.', [])).'</p>'
            .'<h2>'.e(__('What you get in each lead', [])).'</h2>'
            .'<p>'.e(__('You receive practical details such as name, number, city intent, and service requirement context to support faster qualification.', [])).'</p>'
            .'</section>';
    }
}
