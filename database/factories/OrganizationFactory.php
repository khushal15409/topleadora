<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'status' => Organization::STATUS_ACTIVE,
            'plan_id' => null,
            'trial_ends_at' => now()->addDays(7),
            'is_trial' => true,
            'mobile_number' => '+910000000000',
            'onboarding_completed' => true,
        ];
    }
}
