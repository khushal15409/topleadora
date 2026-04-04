<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('countries')) {
            return;
        }

        $path = database_path('data/iso3166_names_by_alpha2.php');
        if (! is_file($path)) {
            return;
        }

        /** @var array<string, string> $names */
        $names = require $path;

        /** @var array<string, int> $preferredOrder Lower = earlier in dropdown */
        $preferredOrder = [
            'US' => 10,
            'IN' => 20,
            'GB' => 30,
            'AE' => 40,
            'CA' => 50,
            'AU' => 60,
            'SG' => 70,
            'DE' => 80,
            'FR' => 90,
        ];

        /** @var array<string, string> $urlSlugOverrides Match legacy landing location_slug */
        $slugOverrides = [
            'US' => 'usa',
            'IN' => 'india',
            'GB' => 'uk',
            'AE' => 'uae',
            'CA' => 'canada',
        ];

        $usedSlugs = [];
        $i = 0;
        foreach ($names as $code => $name) {
            $code = strtoupper($code);
            if (strlen($code) !== 2) {
                continue;
            }
            $i++;
            $slug = $slugOverrides[$code] ?? Str::slug($name);
            $base = $slug;
            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.strtolower($code);
            }
            $usedSlugs[] = $slug;

            Country::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'url_slug' => $slug,
                    'is_active' => true,
                    'sort_order' => $preferredOrder[$code] ?? (100 + $i),
                ]
            );
        }
    }
}
