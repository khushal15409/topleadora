<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Organization;
use App\Models\Setting;
use App\Models\User;
use App\Support\Roles;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_free_mode_allows_access_even_if_trial_expired(): void
    {
        Setting::query()->create(['key' => 'payment_enabled', 'value' => '0']);

        $org = Organization::factory()->create([
            'onboarding_completed' => true,
            'trial_ends_at' => now()->subDays(10),
        ]);
        $user = User::factory()->create(['organization_id' => $org->id]);
        $user->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        Lead::factory()->create([
            'organization_id' => $org->id,
            'assigned_to' => null,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.leads.index'))
            ->assertOk();
    }
}

