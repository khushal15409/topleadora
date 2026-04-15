<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Permissions;
use App\Support\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = 'web';

        $permissionKeys = [
            Permissions::DASHBOARD_VIEW,
            Permissions::USERS_MANAGE,
            Permissions::ROLES_MANAGE,
            Permissions::ORGANIZATION_MANAGE,
            Permissions::ORGANIZATIONS_MANAGE,
            Permissions::SETTINGS_MANAGE,
            'manage_users',
            'manage_leads',
            'manage_plans',
            'view_reports',
            'manage_broadcast',
        ];

        foreach ($permissionKeys as $name) {
            Permission::findOrCreate($name, $guard);
        }

        $superAdmin = Role::findOrCreate(Roles::SUPER_ADMIN, $guard);
        $organization = Role::findOrCreate(Roles::ORGANIZATION, $guard);
        $orgAdmin = Role::findOrCreate(Roles::ORG_ADMIN, $guard);
        $sales = Role::findOrCreate(Roles::SALES, $guard);
        $apiClient = Role::findOrCreate(Roles::API_CLIENT, $guard);

        $superAdmin->syncPermissions(Permission::query()->where('guard_name', $guard)->get());

        $tenantPerms = [
            Permissions::DASHBOARD_VIEW,
            Permissions::ORGANIZATION_MANAGE,
        ];

        $organization->syncPermissions($tenantPerms);
        $orgAdmin->syncPermissions($tenantPerms);
        $sales->syncPermissions($tenantPerms);
        $apiClient->syncPermissions([Permissions::DASHBOARD_VIEW]);

        User::query()->whereDoesntHave('roles')->each(function (User $user) use ($organization): void {
            $user->assignRole($organization);
        });
    }
}
