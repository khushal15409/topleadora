<?php

namespace App\Support;

/**
 * Spatie role names (guard: web). Use these instead of hard-coded strings.
 */
final class Roles
{
    public const SUPER_ADMIN = 'SuperAdmin';

    public const ORGANIZATION = 'Organization';

    /** Organization workspace admin: all leads in tenant */
    public const ORG_ADMIN = 'Org Admin';

    /** Sales rep: leads assigned_to this user only */
    public const SALES = 'Sales';

    /** API Client: primarily uses OTP/WhatsApp API services */
    public const API_CLIENT = 'API Client';
}
