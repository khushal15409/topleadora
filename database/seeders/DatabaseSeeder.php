<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Organization;
use App\Models\User;
use App\Support\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            PlanSeeder::class,
        ]);

        // UserFactory default password is the literal string "password" (see database/factories/UserFactory.php).
        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'test@example.com',
        ]);
        $user->syncRoles([Roles::SUPER_ADMIN]);

        $demoOrg = Organization::query()->create([
            'name' => 'Demo Organization',
            'slug' => 'demo-organization',
            'status' => Organization::STATUS_ACTIVE,
            'plan_id' => null,
            'trial_ends_at' => now()->addDays(7),
            'is_trial' => true,
            'mobile_number' => '+910000000000',
            'onboarding_completed' => true,
        ]);

        $orgUser = User::factory()->create([
            'organization_id' => $demoOrg->id,
            'name' => 'Organization User',
            'email' => 'org@example.com',
        ]);
        $orgUser->syncRoles([Roles::ORGANIZATION, Roles::ORG_ADMIN]);

        Lead::query()->create([
            'organization_id' => $demoOrg->id,
            'assigned_to' => $orgUser->id,
            'name' => 'Demo Lead (assigned)',
            'email' => 'demo-lead@example.com',
            'phone' => '+919876543210',
            'status' => Lead::STATUS_NEW,
            'source' => Lead::SOURCE_WHATSAPP,
            'notes' => 'Sample record for multi-tenant leads UI.',
            'next_followup_at' => now()->addDay(),
        ]);
        Lead::query()->create([
            'organization_id' => $demoOrg->id,
            'assigned_to' => null,
            'name' => 'Demo Lead (unassigned)',
            'email' => 'unassigned@example.com',
            'phone' => '+919998887766',
            'status' => Lead::STATUS_CONTACTED,
            'source' => Lead::SOURCE_WEBSITE,
        ]);
    }
}
