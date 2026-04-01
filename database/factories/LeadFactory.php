<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'assigned_to' => null,
            'name' => fake()->name(),
            'email' => fake()->boolean(60) ? fake()->safeEmail() : null,
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(Lead::pipelineStages()),
            'source' => fake()->randomElement(array_keys(Lead::sourceOptions())),
            'notes' => null,
            'next_followup_at' => null,
            'followup_completed_at' => null,
        ];
    }
}
