<?php

namespace App\Support;

/**
 * Spatie permission names (guard: web).
 */
final class Permissions
{
    public const DASHBOARD_VIEW = 'dashboard.view';

    public const USERS_MANAGE = 'users.manage';

    public const ROLES_MANAGE = 'roles.manage';

    public const ORGANIZATION_MANAGE = 'organization.manage';

    /** Super Admin: manage users with Organization role (front registrations / org accounts) */
    public const ORGANIZATIONS_MANAGE = 'organizations.manage';

    public const SETTINGS_MANAGE = 'settings.manage';
}
