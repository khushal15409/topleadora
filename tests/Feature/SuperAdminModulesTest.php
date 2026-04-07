<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use App\Support\Roles;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role as SpatieRole;
use Tests\TestCase;

class SuperAdminModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_super_admin_can_open_roles_page(): void
    {
        $super = User::factory()->create();
        $super->syncRoles([Roles::SUPER_ADMIN]);

        $this->actingAs($super)
            ->get(route('admin.roles.index'))
            ->assertOk()
            ->assertSee('Roles');
    }

    public function test_super_admin_can_create_role(): void
    {
        $super = User::factory()->create();
        $super->syncRoles([Roles::SUPER_ADMIN]);

        $this->actingAs($super)
            ->post(route('admin.roles.store'), [
                'name' => 'Test Role',
                'permissions' => ['manage_users'],
            ])
            ->assertRedirect(route('admin.roles.index'));

        $this->assertNotNull(SpatieRole::query()->where('name', 'Test Role')->first());
    }

    public function test_super_admin_can_open_broadcast_usage(): void
    {
        $super = User::factory()->create();
        $super->syncRoles([Roles::SUPER_ADMIN]);

        $this->actingAs($super)
            ->get(route('admin.broadcast-usage.index'))
            ->assertOk()
            ->assertSee('Broadcast usage');
    }

    public function test_super_admin_can_open_analytics(): void
    {
        $super = User::factory()->create();
        $super->syncRoles([Roles::SUPER_ADMIN]);

        $this->actingAs($super)
            ->get(route('admin.analytics.index'))
            ->assertOk()
            ->assertSee('Analytics');
    }

    public function test_super_admin_can_save_integrations_settings(): void
    {
        $super = User::factory()->create();
        $super->syncRoles([Roles::SUPER_ADMIN]);

        $this->actingAs($super)
            ->put(route('admin.integrations.update'), [
                'whatsapp_enabled' => true,
                'whatsapp_api_token' => 'token-123',
                'smtp_enabled' => true,
                'smtp_host' => 'smtp.example.com',
                'smtp_port' => 587,
            ])
            ->assertRedirect(route('admin.integrations.index'));

        $this->actingAs($super)
            ->get(route('admin.integrations.index'))
            ->assertOk()
            ->assertSee('smtp.example.com');
    }

    public function test_organization_user_cannot_open_super_admin_modules(): void
    {
        $org = Organization::factory()->create();
        $u = User::factory()->create(['organization_id' => $org->id]);
        $u->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        $this->actingAs($u)->get(route('admin.roles.index'))->assertForbidden();
        $this->actingAs($u)->get(route('admin.broadcast-usage.index'))->assertForbidden();
        $this->actingAs($u)->get(route('admin.analytics.index'))->assertForbidden();
        $this->actingAs($u)->get(route('admin.integrations.index'))->assertForbidden();
    }
}
