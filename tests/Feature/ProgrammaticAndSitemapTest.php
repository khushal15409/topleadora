<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgrammaticAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_index_points_to_child_sitemaps(): void
    {
        $r = $this->get('/sitemap.xml');
        $r->assertOk();
        $r->assertSee('sitemapindex', false);
        $r->assertSee(route('sitemap.main', absolute: true), false);
        $r->assertSee(route('sitemap.blog', absolute: true), false);
        $r->assertSee(route('sitemap.leads', absolute: true), false);
    }

    public function test_sitemap_main_contains_core_routes(): void
    {
        $r = $this->get('/sitemap-main.xml');
        $r->assertOk();
        $r->assertSee('<loc>'.url('/').'</loc>', false);
        $r->assertSee(url('/features'), false);
        $r->assertSee(url('/pricing'), false);
        $r->assertSee(route('blog.index'), false);
        $r->assertSee(route('contact'), false);
        $r->assertSee('<priority>1.0</priority>', false);
    }

    public function test_programmatic_lead_page_renders_when_india_country_exists(): void
    {
        Country::query()->create([
            'code' => 'IN',
            'name' => 'India',
            'url_slug' => 'india',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        Service::query()->create([
            'name' => 'Personal Loan',
            'slug' => 'loan',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $r = $this->get('/leads/loan-ahmedabad');
        $r->assertOk();
        $r->assertSee('Ahmedabad', false);
        $r->assertSee('Personal Loan', false);
    }

    public function test_sitemap_leads_lists_programmatic_url_when_no_landing_row(): void
    {
        Country::query()->create([
            'code' => 'IN',
            'name' => 'India',
            'url_slug' => 'india',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        Service::query()->create([
            'name' => 'Personal Loan',
            'slug' => 'loan',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $r = $this->get('/sitemap-leads.xml');
        $r->assertOk();
        $r->assertSee(route('leads.landing', 'loan-ahmedabad'), false);
    }
}
