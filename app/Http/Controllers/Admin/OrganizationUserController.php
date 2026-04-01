<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class OrganizationUserController extends Controller
{
    public function index(Request $request): View
    {
        $organizationId = $request->query('organization_id');
        $roleFilter = $request->query('role');

        $organizations = Organization::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $roles = Role::query()
            ->where('guard_name', 'web')
            ->where('name', '!=', Roles::SUPER_ADMIN)
            ->orderBy('name')
            ->get(['id', 'name']);

        $users = User::query()
            ->with(['organization', 'roles'])
            ->whereDoesntHave('roles', static function (Builder $q): void {
                $q->where('name', Roles::SUPER_ADMIN);
            })
            ->when(
                $organizationId !== null && $organizationId !== '',
                static function (Builder $q) use ($organizationId): void {
                    $q->where('organization_id', (int) $organizationId);
                }
            )
            ->when(
                $roleFilter !== null && $roleFilter !== '',
                static function (Builder $q) use ($roleFilter): void {
                    $q->whereHas('roles', static function (Builder $rq) use ($roleFilter): void {
                        $rq->where('name', $roleFilter);
                    });
                }
            )
            ->orderByDesc('id')
            ->get();

        return view('admin.organization-users.index', compact('users', 'organizations', 'roles', 'organizationId', 'roleFilter'));
    }

    public function show(User $user): View
    {
        if ($user->hasRole(Roles::SUPER_ADMIN)) {
            abort(404);
        }

        $user->load(['organization', 'roles']);

        return view('admin.organization-users.show', compact('user'));
    }
}
