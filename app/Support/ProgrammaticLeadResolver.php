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

        $base['meta_title'] = Str::limit(trim(strip_tags($rawTitle)), 72);
        $base['meta_description'] = SeoMeta::formatDescription($rawDesc);
        $base['meta_keywords'] = Str::limit($service->name.', '.$cityLabel.', India', 512);
        $base['robots_meta'] = 'index,follow';

        $base['hero_headline'] = __('Apply for :service in :city', [
            'service' => $service->name,
            'city' => $cityLabel,
        ]);
        $base['hero_subheadline'] = __('One short form — quick callback. Licensed partners may assist you in :city as applicable.', [
            'city' => $cityLabel,
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
                'q' => __('Who processes my :service request in :city?', ['service' => $service->name, 'city' => $cityLabel]),
                'a' => __('We collect your details on this secure page and route them to licensed partners or internal teams who can assist :city applicants, subject to eligibility.', ['city' => $cityLabel]),
            ],
            [
                'q' => __('Is the form free?', []),
                'a' => __('Yes — submitting this form is free. Any product or fee is always disclosed by the partner you choose.', []),
            ],
            [
                'q' => __('How fast will I get a callback?', []),
                'a' => __('During business hours we aim to respond quickly — often within minutes. Off-hours requests are queued for the next working window.', []),
            ],
        ];
    }

    private static function uniqueSeoBodyHtml(Service $service, string $cityLabel): string
    {
        return '<section class="programmatic-seo-body">'
            .'<h2>'.e(__('Why apply for :service in :city?', ['service' => $service->name, 'city' => $cityLabel])).'</h2>'
            .'<p>'.e(__('If you are based in :city and exploring :service options, this page is tailored for your location with India-focused guidance and a secure intake form.', ['city' => $cityLabel, 'service' => $service->name])).'</p>'
            .'<h2>'.e(__('What happens after you submit?', [])).'</h2>'
            .'<p>'.e(__('Your information is used only to assess fit and connect you with the right team. We do not sell phone numbers; messaging is transactional.', [])).'</p>'
            .'<h2>'.e(__('Transparency', [])).'</h2>'
            .'<p>'.e(__('Final approval timelines, rates, and documents depend on the partner product. We help you start the conversation without obligation.', [])).'</p>'
            .'</section>';
    }
}
