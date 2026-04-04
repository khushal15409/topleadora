<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\LandingPage;
use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $base = url('/');
        $now = now()->toAtomString();

        $static = [
            ['loc' => $base.'/', 'lastmod' => $now],
            ['loc' => url('/features'), 'lastmod' => $now],
            ['loc' => url('/pricing'), 'lastmod' => $now],
            ['loc' => route('blog.index'), 'lastmod' => $now],
        ];

        $leadLandings = [];
        if (Schema::hasTable('landing_pages') && LandingPage::query()->where('is_active', true)->exists()) {
            $leadLandings = LandingPage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('slug')
                ->get(['slug', 'updated_at'])
                ->map(fn (LandingPage $p) => [
                    'loc' => route('leads.landing', $p->slug),
                    'lastmod' => ($p->updated_at ?? now())->toAtomString(),
                ])
                ->all();
        } elseif (Schema::hasTable('lead_landing_pages') && LeadLandingPage::query()->where('is_active', true)->exists()) {
            $leadLandings = LeadLandingPage::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('slug')
                ->get(['slug', 'updated_at'])
                ->map(fn (LeadLandingPage $p) => [
                    'loc' => route('leads.landing', $p->slug),
                    'lastmod' => ($p->updated_at ?? now())->toAtomString(),
                ])
                ->all();
        } else {
            $leadLandings = LeadNiche::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['slug', 'updated_at'])
                ->map(fn (LeadNiche $n) => [
                    'loc' => route('leads.landing', $n->slug),
                    'lastmod' => ($n->updated_at ?? now())->toAtomString(),
                ])
                ->all();
        }

        $posts = BlogPost::published()
            ->get(['slug', 'updated_at'])
            ->map(fn (BlogPost $p) => [
                'loc' => route('blog.show', $p->slug),
                'lastmod' => ($p->updated_at ?? now())->toAtomString(),
            ])
            ->all();

        $urls = array_merge($static, $leadLandings, $posts);

        $xml = view('sitemap.xml', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
