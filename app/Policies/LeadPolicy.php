<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use App\Support\Roles;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(Roles::SUPER_ADMIN)) {
            return true;
        }

        return $user->organization_id !== null
            && $user->hasAnyRole([Roles::ORGANIZATION, Roles::ORG_ADMIN, Roles::SALES]);
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->hasRole(Roles::SUPER_ADMIN)) {
            return true;
        }

        if ((int) $lead->organization_id !== (int) $user->organization_id) {
            return false;
        }

        if ($user->canViewAllOrganizationLeads()) {
            return true;
        }

        return $lead->assigned_to !== null
            && (int) $lead->assigned_to === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Lead $lead): bool
    {
        return $this->view($user, $lead);
    }
}
