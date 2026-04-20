<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\LandingPage;
use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use App\Models\Service;
use App\Support\ProgrammaticLeadResolver;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    /**
     * Sitemap index: /sitemap.xml — points to split child sitemaps for crawl budget + organization.
     */
    public function index(): Response
    {
        $host = request()->getHost();
        $ttlSeconds = 3600;
        $cacheKey = "sitemap:index:{$host}";

        $xml = Cache::remember($cacheKey, $ttlSeconds, function () {
            $now = now()->toAtomString();
            $sitemaps = [
                ['loc' => route('sitemap.main', absolute: true), 'lastmod' => $now],
                ['loc' => route('sitemap.blog', absolute: true), 'lastmod' => $now],
                ['loc' => route('sitemap.leads', absolute: true), 'lastmod' => $now],
            ];

            return view('sitemap.index', compact('sitemaps'))->render();
        });

        return $this->xmlResponse($xml);
    }

    /** Static marketing URLs: home, features, pricing, blog index, contact. */
    public function main(): Response
    {
        $host = request()->getHost();
        $ttlSeconds = 3600;
        $cacheKey = "sitemap:main:{$host}";

        $xml = Cache::remember($cacheKey, $ttlSeconds, function () {
            $now = now()->toAtomString();
            $sm = config('programmatic_seo.sitemap', []);

            $urls = [
                $this->entry(url('/'), $now, $sm['home_changefreq'] ?? 'daily', $sm['home_priority'] ?? '1.0'),
                $this->entry(url('/features'), $now, $sm['default_changefreq'] ?? 'weekly', $sm['main_priority'] ?? '0.85'),
                $this->entry(url('/pricing'), $now, $sm['default_changefreq'] ?? 'weekly', $sm['main_priority'] ?? '0.85'),
                $this->entry(route('blog.index'), $now, $sm['blog_changefreq'] ?? 'weekly', $sm['blog_priority'] ?? '0.70'),
                $this->entry(route('contact'), $now, $sm['default_changefreq'] ?? 'weekly', $sm['main_priority'] ?? '0.85'),
            ];

            return view('sitemap.urlset', ['urls' => $urls])->render();
        });

        return $this->xmlResponse($xml);
    }

    public function blog(): Response
    {
        $host = request()->getHost();
        $ttlSeconds = 3600;
        $cacheKey = "sitemap:blog:{$host}";

        $xml = Cache::remember($cacheKey, $ttlSeconds, function () {
            $sm = config('programmatic_seo.sitemap', []);
            $urls = BlogPost::published()
                ->get(['slug', 'updated_at'])
                ->map(fn (BlogPost $p) => $this->entry(
                    route('blog.show', $p->slug),
                    ($p->updated_at ?? now())->toAtomString(),
                    $sm['default_changefreq'] ?? 'weekly',
                    $sm['post_priority'] ?? '0.65',
                ))
                ->all();

            return view('sitemap.urlset', ['urls' => $urls])->render();
        });

        return $this->xmlResponse($xml);
    }

    /**
     * All /leads/{slug} URLs: DB landings + synthetic service–city pairs (deduped).
     */
    public function leads(): Response
    {
        $host = request()->getHost();
        $ttlSeconds = 3600;
        $cacheKey = "sitemap:leads:{$host}";

        $xml = Cache::remember($cacheKey, $ttlSeconds, function () {
            $sm = config('programmatic_seo.sitemap', []);
            $seen = [];
            $urls = [];

            foreach ($this->leadLandingUrls() as $row) {
                $loc = $row['loc'];
                if (isset($seen[$loc])) {
                    continue;
                }
                $seen[$loc] = true;
                $urls[] = $this->entry(
                    $loc,
                    $row['lastmod'],
                    $sm['leads_changefreq'] ?? 'weekly',
                    $sm['leads_priority'] ?? '0.90',
                );
            }

            foreach ($this->syntheticProgrammaticLeadUrls() as $row) {
                $loc = $row['loc'];
                if (isset($seen[$loc])) {
                    continue;
                }
                $seen[$loc] = true;
                $urls[] = $this->entry(
                    $loc,
                    $row['lastmod'],
                    $sm['leads_changefreq'] ?? 'weekly',
                    $sm['leads_priority'] ?? '0.90',
                );
            }

            return view('sitemap.urlset', ['urls' => $urls])->render();
        });

        return $this->xmlResponse($xml);
    }

    /**
     * @return list<array{loc: string, lastmod: string}>
     */
    private function leadLandingUrls(): array
    {
        $now = now()->toAtomString();

        if (Schema::hasTable('landing_pages') && LandingPage::query()->where('is_active', true)->exists()) {
            return LandingPage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('slug')
                ->get(['slug', 'updated_at'])
                ->map(fn (LandingPage $p) => [
                    'loc' => route('leads.landing', $p->slug),
                    'lastmod' => ($p->updated_at ?? now())->toAtomString(),
                ])
                ->all();
        }

        if (Schema::hasTable('lead_landing_pages') && LeadLandingPage::query()->where('is_active', true)->exists()) {
            return LeadLandingPage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('slug')
                ->get(['slug', 'updated_at'])
                ->map(fn (LeadLandingPage $p) => [
                    'loc' => route('leads.landing', $p->slug),
                    'lastmod' => ($p->updated_at ?? now())->toAtomString(),
                ])
                ->all();
        }

        return LeadNiche::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['slug', 'updated_at'])
            ->map(fn (LeadNiche $n) => [
                'loc' => route('leads.landing', $n->slug),
                'lastmod' => ($n->updated_at ?? now())->toAtomString(),
            ])
            ->all();
    }

    /**
     * @return list<array{loc: string, lastmod: string}>
     */
    private function syntheticProgrammaticLeadUrls(): array
    {
        if (! ProgrammaticLeadResolver::enabled() || ! Schema::hasTable('services')) {
            return [];
        }

        $now = now()->toAtomString();
        $dbSlugs = [];
        if (Schema::hasTable('landing_pages')) {
            $dbSlugs = LandingPage::query()->where('is_active', true)->pluck('slug')->all();
        }
        $dbSlugSet = array_fill_keys($dbSlugs, true);

        $urls = [];
        $services = Service::query()->activeOrdered()->get(['slug', 'updated_at']);
        $cities = ProgrammaticLeadResolver::citySlugs();

        foreach ($services as $service) {
            foreach ($cities as $citySlug) {
                $full = $service->slug.'-'.$citySlug;
                if (isset($dbSlugSet[$full])) {
                    continue;
                }
                $urls[] = [
                    'loc' => route('leads.landing', $full),
                    'lastmod' => $now,
                ];
            }
        }

        return $urls;
    }

    /**
     * @return array{loc: string, lastmod: string, changefreq: string, priority: string}
     */
    private function entry(string $loc, string $lastmod, string $changefreq, string $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    private function urlsetResponse(array $urls): Response
    {
        $xml = view('sitemap.urlset', compact('urls'))->render();

        return $this->xmlResponse($xml);
    }

    private function xmlResponse(string $xml): Response
    {
        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
