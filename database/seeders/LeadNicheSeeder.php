<?php

namespace Database\Seeders;

use App\Models\LeadNiche;
use Illuminate\Database\Seeder;

class LeadNicheSeeder extends Seeder
{
    public function run(): void
    {
        $rows = array_merge(
            require database_path('data/lead_niches_core.php'),
            require database_path('data/lead_niches_extended.php'),
        );

        foreach ($rows as $order => $row) {
            $row = $this->replaceAppPlaceholder($row);
            $row['sort_order'] = $order;
            $row['is_active'] = true;
            LeadNiche::query()->updateOrCreate(
                ['slug' => $row['slug']],
                $row
            );
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function replaceAppPlaceholder(array $row): array
    {
        $app = config('app.name');

        foreach ($row as $key => $value) {
            if (is_string($value)) {
                $row[$key] = str_replace('{app}', $app, $value);
            } elseif (is_array($value)) {
                $row[$key] = $this->replaceAppInArray($value, $app);
            }
        }

        return $row;
    }

    /**
     * @param  array<mixed>  $data
     * @return array<mixed>
     */
    private function replaceAppInArray(array $data, string $app): array
    {
        $out = [];
        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $out[$k] = str_replace('{app}', $app, $v);
            } elseif (is_array($v)) {
                $out[$k] = $this->replaceAppInArray($v, $app);
            } else {
                $out[$k] = $v;
            }
        }

        return $out;
    }
}
