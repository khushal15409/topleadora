<?php

namespace App\Support;

use App\Models\Country;
use App\Models\LandingPage;
use App\Models\Service;
use Illuminate\Support\Facades\Schema;

/**
 * Internal links from blog to /leads/* (DB landing preferred, else programmatic).
 */
class BlogRelatedLeadLinks
{
    /**
     * @return list<array{url: string, label: string, hint: string}>
     */
    public static function forSidebar(int $limit = 8): array
    {
        if (! Schema::hasTable('services')) {
            return [];
        }

        $services = Service::query()->activeOrdered()->get();
        if ($services->isEmpty()) {
            return [];
        }

        $indiaId = Country::query()->where('code', 'IN')->where('is_active', true)->value('id');

        $firstCityRow = collect(config('programmatic_seo.india_cities', []))->first();
        $firstCitySlug = is_array($firstCityRow) ? (string) ($firstCityRow['slug'] ?? 'mumbai') : 'mumbai';

        $out = [];
        foreach ($services as $service) {
            if (count($out) >= $limit) {
                break;
            }

            if (Schema::hasTable('landing_pages') && $indiaId !== null) {
                $lp = LandingPage::query()
                    ->activeOrdered()
                    ->where('service_id', $service->id)
                    ->where('country_id', (int) $indiaId)
                    ->first();
                if ($lp !== null) {
                    $out[] = [
                        'url' => route('leads.landing', $lp->slug),
                        'label' => $service->name,
                        'hint' => __('India offers'),
                    ];

                    continue;
                }
            }

            if (ProgrammaticLeadResolver::enabled() && ProgrammaticLeadResolver::cityLabel($firstCitySlug) !== null) {
                $slug = $service->slug.'-'.$firstCitySlug;
                $out[] = [
                    'url' => route('leads.landing', $slug),
                    'label' => $service->name,
                    'hint' => ProgrammaticLeadResolver::cityLabel($firstCitySlug) ?? '',
                ];
            }
        }

        return $out;
    }
}
