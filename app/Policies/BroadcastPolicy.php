<?php

namespace App\Policies;

use App\Models\Broadcast;
use App\Models\User;
use App\Support\Roles;

class BroadcastPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(Roles::SUPER_ADMIN)) {
            return false;
        }

        return $user->organization_id !== null
            && $user->hasAnyRole([Roles::ORGANIZATION, Roles::ORG_ADMIN, Roles::SALES]);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }
}
