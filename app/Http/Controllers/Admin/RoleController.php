<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Support\Permissions;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $roles = SpatieRole::query()
            ->where('guard_name', 'web')
            ->withCount('permissions')
            ->orderBy('name')
            ->get();

        $permissions = $this->ensureAndListPermissions();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $data = $request->validated();

        $role = SpatieRole::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        $perms = $this->normalizePermNames($data['permissions'] ?? []);
        $role->syncPermissions($perms);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('Role created.'));
    }

    public function update(UpdateRoleRequest $request, SpatieRole $role): RedirectResponse
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        if ($role->name === Roles::SUPER_ADMIN) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors(['role' => __('Super Admin role cannot be edited.')]);
        }

        $data = $request->validated();

        $role->update([
            'name' => $data['name'],
        ]);

        $perms = $this->normalizePermNames($data['permissions'] ?? []);
        $role->syncPermissions($perms);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('Role updated.'));
    }

    public function destroy(Request $request, SpatieRole $role): RedirectResponse
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        if (in_array($role->name, [Roles::SUPER_ADMIN, Roles::ORGANIZATION], true)) {
            return redirect()
                ->route('admin.roles.index')
                ->withErrors(['role' => __('This role cannot be deleted.')]);
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', __('Role deleted.'));
    }

    /**
     * @return array<int, array{name: string, label: string}>
     */
    private function ensureAndListPermissions(): array
    {
        $guard = 'web';

        $suggested = [
            // existing app permissions
            Permissions::DASHBOARD_VIEW,
            Permissions::USERS_MANAGE,
            Permissions::ROLES_MANAGE,
            Permissions::ORGANIZATION_MANAGE,
            Permissions::ORGANIZATIONS_MANAGE,
            Permissions::SETTINGS_MANAGE,

            // requested examples (alias-friendly keys)
            'manage_users',
            'manage_leads',
            'manage_plans',
            'view_reports',
            'manage_broadcast',
        ];

        foreach ($suggested as $name) {
            SpatiePermission::findOrCreate($name, $guard);
        }

        return SpatiePermission::query()
            ->where('guard_name', $guard)
            ->orderBy('name')
            ->get(['name'])
            ->map(fn (SpatiePermission $p) => [
                'name' => $p->name,
                'label' => str($p->name)->replace('.', ' ')->replace('_', ' ')->title()->toString(),
            ])
            ->all();
    }

    /**
     * @param  array<int, string>  $names
     * @return array<int, string>
     */
    private function normalizePermNames(array $names): array
    {
        $names = array_values(array_unique(array_filter(array_map('strval', $names))));

        return array_values(array_filter($names, fn (string $n) => $n !== ''));
    }
}
