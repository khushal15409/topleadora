<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\LeadNiche;
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
            'city' => fake()->boolean(40) ? fake()->city() : null,
            'niche' => fake()->boolean(30) ? LeadNiche::query()->inRandomOrder()->value('slug') : null,
            'source_page' => null,
            'status' => fake()->randomElement(Lead::pipelineStages()),
            'source' => fake()->randomElement(array_keys(Lead::sourceOptions())),
            'notes' => null,
            'next_followup_at' => null,
            'followup_completed_at' => null,
        ];
    }
}
