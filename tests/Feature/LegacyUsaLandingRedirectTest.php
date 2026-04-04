<?php

namespace Tests\Feature;

use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyUsaLandingRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_dash_usa_slug_redirects_when_canonical_landing_exists(): void
    {
        $niche = LeadNiche::query()->create([
            'slug' => 'loan',
            'label' => 'Loan',
            'is_active' => true,
            'sort_order' => 0,
            'meta_title' => 'Loan',
            'meta_description' => 'Test',
            'hero_headline' => 'Loan',
        ]);

        LeadLandingPage::query()->create([
            'lead_niche_id' => $niche->id,
            'slug' => 'loan',
            'location_slug' => 'usa',
            'location_label' => 'United States',
            'meta_title' => 'Loan US',
            'meta_description' => 'Test',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->get('/leads/loan-usa')
            ->assertRedirect(route('leads.landing', 'loan'));
    }
}
