<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Starter', 'slug' => 'starter', 'price_monthly' => 299, 'currency' => 'INR', 'sort_order' => 10],
            ['name' => 'Pro', 'slug' => 'professional', 'price_monthly' => 599, 'currency' => 'INR', 'sort_order' => 20],
            ['name' => 'Business', 'slug' => 'enterprise', 'price_monthly' => 999, 'currency' => 'INR', 'sort_order' => 30],
        ];

        foreach ($rows as $row) {
            Plan::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'price_monthly' => $row['price_monthly'],
                    'currency' => $row['currency'],
                    'is_active' => true,
                    'sort_order' => $row['sort_order'],
                ]
            );
        }
    }
}
