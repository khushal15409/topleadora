<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\LandingPage;
use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use App\Models\Service;
use App\Support\LeadLandingSeoGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Builds services + landing_pages from legacy lead_niches / lead_landing_pages when present.
 */
class MarketingStructureSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('services') || ! Schema::hasTable('countries')) {
            return;
        }

        $this->call(CountrySeeder::class);

        if (! Schema::hasTable('lead_niches')) {
            return;
        }

        foreach (LeadNiche::query()->orderBy('sort_order')->orderBy('label')->get() as $niche) {
            $page = $niche->toPageArray();
            unset($page['meta_title'], $page['meta_description'], $page['meta_keywords']);

            Service::query()->updateOrCreate(
                ['slug' => $niche->slug],
                [
                    'name' => $niche->label,
                    'is_active' => $niche->is_active,
                    'sort_order' => $niche->sort_order ?? 0,
                    'default_content_json' => $page,
                ]
            );
        }

        if (! Schema::hasTable('landing_pages') || ! Schema::hasTable('lead_landing_pages')) {
            return;
        }

        foreach (LeadLandingPage::query()->with('niche')->orderBy('sort_order')->orderBy('slug')->get() as $legacy) {
            $niche = $legacy->niche;
            if ($niche === null) {
                continue;
            }

            $service = Service::query()->where('slug', $niche->slug)->first();
            if ($service === null) {
                continue;
            }

            $country = Country::query()->where('url_slug', $legacy->location_slug)->first();
            if ($country === null) {
                $codeFromSlug = [
                    'usa' => 'US',
                    'india' => 'IN',
                    'uk' => 'GB',
                    'uae' => 'AE',
                    'canada' => 'CA',
                ][$legacy->location_slug] ?? null;
                if ($codeFromSlug !== null) {
                    $country = Country::query()->where('code', $codeFromSlug)->first();
                }
            }
            if ($country === null) {
                continue;
            }

            $focus = LeadLandingSeoGenerator::focusKeywordsForNiche($niche->slug);
            $content = is_array($service->default_content_json) ? $service->default_content_json : [];

            LandingPage::query()->updateOrCreate(
                [
                    'service_id' => $service->id,
                    'country_id' => $country->id,
                ],
                [
                    'slug' => $legacy->slug,
                    'meta_title' => $legacy->meta_title,
                    'meta_description' => $legacy->meta_description,
                    'meta_keywords' => $legacy->meta_keywords,
                    'robots_meta' => $legacy->robots_meta ?? 'index,follow',
                    'seo_body' => $legacy->seo_body ?? LeadLandingSeoGenerator::bodyHtml($niche, $legacy->location_label, $focus),
                    'content_json' => array_replace_recursive($content, [
                        'hero_headline' => $legacy->hero_headline_override ?? ($content['hero_headline'] ?? $niche->hero_headline),
                        'hero_subheadline' => $legacy->hero_subheadline_override ?? ($content['hero_subheadline'] ?? $niche->hero_subheadline),
                        'aggregate_rating_value' => $legacy->aggregate_rating_value,
                        'aggregate_rating_count' => $legacy->aggregate_rating_count,
                    ]),
                    'is_active' => $legacy->is_active,
                    'sort_order' => $legacy->sort_order ?? 0,
                ]
            );
        }

        if (Schema::hasTable('marketing_form_fields')) {
            $this->call(MarketingFormFieldSeeder::class);
        }
    }
}
