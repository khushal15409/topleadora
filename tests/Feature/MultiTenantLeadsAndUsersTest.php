<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Organization;
use App\Models\User;
use App\Support\Roles;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenantLeadsAndUsersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_super_admin_can_list_organization_users(): void
    {
        $org = Organization::factory()->create();
        $tenant = User::factory()->create([
            'organization_id' => $org->id,
        ]);
        $tenant->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        $super = User::factory()->create(['organization_id' => null]);
        $super->syncRoles([Roles::SUPER_ADMIN]);

        $response = $this->actingAs($super)->get(route('admin.users.index'));

        // Super admin's email appears in the layout (navbar); assert tenant is listed.
        $response->assertOk()
            ->assertSee($tenant->email)
            ->assertSee($org->name);
    }

    public function test_tenant_user_cannot_access_super_admin_users_module(): void
    {
        $org = Organization::factory()->create();
        $tenant = User::factory()->create(['organization_id' => $org->id]);
        $tenant->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        $response = $this->actingAs($tenant)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_dashboard_root_redirects_to_crm_for_tenant(): void
    {
        $org = Organization::factory()->create([
            'onboarding_completed' => true,
            'trial_ends_at' => now()->addDays(3),
        ]);
        $admin = User::factory()->create(['organization_id' => $org->id]);
        $admin->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('dashboard.leads.index'));
    }

    public function test_org_admin_sees_all_leads_in_tenant(): void
    {
        $org = Organization::factory()->create([
            'onboarding_completed' => true,
            'trial_ends_at' => now()->addDays(3),
        ]);
        $admin = User::factory()->create(['organization_id' => $org->id]);
        $admin->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        Lead::factory()->create([
            'organization_id' => $org->id,
            'assigned_to' => null,
            'name' => 'Unassigned Org Lead',
        ]);
        Lead::factory()->create([
            'organization_id' => $org->id,
            'assigned_to' => $admin->id,
            'name' => 'Assigned Org Lead',
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard.leads.index'));

        $response->assertOk()
            ->assertSee('Unassigned Org Lead')
            ->assertSee('Assigned Org Lead');
    }

    public function test_sales_user_sees_only_assigned_leads(): void
    {
        $org = Organization::factory()->create([
            'onboarding_completed' => true,
            'trial_ends_at' => now()->addDays(3),
        ]);
        $sales = User::factory()->create(['organization_id' => $org->id]);
        $sales->syncRoles([Roles::ORGANIZATION, Roles::SALES]);

        Lead::factory()->create([
            'organization_id' => $org->id,
            'assigned_to' => null,
            'name' => 'Nobody',
        ]);
        Lead::factory()->create([
            'organization_id' => $org->id,
            'assigned_to' => $sales->id,
            'name' => 'Mine',
        ]);

        $response = $this->actingAs($sales)->get(route('dashboard.leads.index'));

        $response->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Nobody');
    }

    public function test_tenant_cannot_open_lead_from_other_organization(): void
    {
        $orgA = Organization::factory()->create([
            'onboarding_completed' => true,
            'trial_ends_at' => now()->addDays(3),
        ]);
        $orgB = Organization::factory()->create([
            'onboarding_completed' => true,
            'trial_ends_at' => now()->addDays(3),
        ]);

        $userB = User::factory()->create(['organization_id' => $orgB->id]);
        $userB->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        $leadA = Lead::factory()->create([
            'organization_id' => $orgA->id,
            'name' => 'Secret A',
        ]);

        $response = $this->actingAs($userB)->get(route('dashboard.leads.edit', $leadA));

        $response->assertNotFound();
    }
}
