<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingLeadCaptureTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_form_stores_marketing_lead_not_crm_lead(): void
    {
        $service = Service::query()->create([
            'name' => 'Loan',
            'slug' => 'loan',
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $country = Country::query()->create([
            'code' => 'US',
            'name' => 'United States',
            'url_slug' => 'usa',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $response = $this->postJson('/leads', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '5551234567',
            'service_id' => $service->id,
            'country_id' => $country->id,
            'city' => 'NYC',
            'source_page' => 'loan',
        ]);

        $response->assertOk()
            ->assertJson(['ok' => true, 'status' => true]);
        $this->assertDatabaseCount('marketing_leads', 1);
        $this->assertDatabaseHas('marketing_leads', [
            'name' => 'Jane Doe',
            'phone' => '5551234567',
            'service_id' => $service->id,
            'country_id' => $country->id,
            'country_code' => 'US',
            'country_name' => 'United States',
            'city' => 'NYC',
            'source_page' => 'loan',
        ]);
        $this->assertDatabaseCount('leads', 0);
    }

    public function test_marketing_lead_stores_optional_city(): void
    {
        $service = Service::query()->create([
            'name' => 'Loan',
            'slug' => 'loan',
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $country = Country::query()->create([
            'code' => 'IN',
            'name' => 'India',
            'url_slug' => 'india',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $response = $this->postJson('/leads', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '9876543210',
            'service_id' => $service->id,
            'country_id' => $country->id,
            'city' => 'Mumbai',
            'source_page' => 'loan-india',
        ]);

        $response->assertOk()
            ->assertJson(['ok' => true, 'status' => true]);
        $this->assertDatabaseHas('marketing_leads', [
            'city' => 'Mumbai',
        ]);
    }

    public function test_marketing_lead_rejects_non_ten_digit_phone(): void
    {
        $service = Service::query()->create([
            'name' => 'Loan',
            'slug' => 'loan',
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $country = Country::query()->create([
            'code' => 'US',
            'name' => 'United States',
            'url_slug' => 'usa',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $response = $this->postJson('/leads', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+15550001111',
            'service_id' => $service->id,
            'country_id' => $country->id,
            'city' => 'NYC',
        ]);

        $response->assertStatus(422)
            ->assertJson(['ok' => false, 'status' => false]);
        $this->assertDatabaseCount('marketing_leads', 0);
    }

    public function test_marketing_lead_requires_core_fields(): void
    {
        $service = Service::query()->create([
            'name' => 'Loan',
            'slug' => 'loan',
            'is_active' => true,
            'sort_order' => 0,
        ]);
        $country = Country::query()->create([
            'code' => 'US',
            'name' => 'United States',
            'url_slug' => 'usa',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $response = $this->postJson('/leads', [
            'name' => '',
            'email' => '',
            'phone' => '',
            'service_id' => $service->id,
            'country_id' => $country->id,
            'city' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson(['ok' => false, 'status' => false])
            ->assertJsonStructure(['errors']);
        $this->assertDatabaseCount('marketing_leads', 0);
    }
}
