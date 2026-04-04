<?php

namespace Database\Seeders;

use App\Models\MarketingFormField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MarketingFormFieldSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('marketing_form_fields')) {
            return;
        }

        MarketingFormField::query()->updateOrCreate(
            ['field_key' => 'message'],
            [
                'label' => 'Additional details',
                'field_type' => 'textarea',
                'is_required' => false,
                'is_active' => true,
                'sort_order' => 10,
            ]
        );
    }
}
