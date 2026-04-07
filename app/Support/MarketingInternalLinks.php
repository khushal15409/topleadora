<?php

namespace App\Support;

use App\Models\LandingPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Cached active marketing landings for internal linking (blog, features, etc.).
 */
class MarketingInternalLinks
{
    /**
     * @return Collection<int, LandingPage>
     */
    public static function featuredLandings(int $limit = 8): Collection
    {
        if (! Schema::hasTable('landing_pages')) {
            return collect();
        }

        return Cache::remember('marketing_internal_landings_'.$limit, 300, static function () use ($limit) {
            return LandingPage::query()
                ->activeOrdered()
                ->with(['service', 'country'])
                ->limit($limit)
                ->get();
        });
    }

    public static function forgetCache(): void
    {
        foreach ([6, 8, 12] as $n) {
            Cache::forget('marketing_internal_landings_'.$n);
        }
    }
}
