<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * US marketing landings use the service slug alone (e.g. /leads/loan), not /leads/loan-usa.
     */
    public function up(): void
    {
        if (Schema::hasTable('lead_landing_pages')) {
            $rows = DB::table('lead_landing_pages')
                ->where('location_slug', 'usa')
                ->where('slug', 'like', '%-usa')
                ->orderBy('id')
                ->get(['id', 'slug']);

            foreach ($rows as $row) {
                $new = substr((string) $row->slug, 0, -4);
                if ($new === '') {
                    continue;
                }
                DB::table('lead_landing_pages')->where('id', $row->id)->update(['slug' => $new]);
            }
        }

        if (Schema::hasTable('landing_pages') && Schema::hasTable('countries')) {
            $usCountryIds = DB::table('countries')
                ->where(function ($q): void {
                    $q->where('code', 'US')->orWhere('url_slug', 'usa');
                })
                ->pluck('id');

            if ($usCountryIds->isNotEmpty()) {
                $rows = DB::table('landing_pages')
                    ->whereIn('country_id', $usCountryIds)
                    ->where('slug', 'like', '%-usa')
                    ->orderBy('id')
                    ->get(['id', 'slug']);

                foreach ($rows as $row) {
                    $new = substr((string) $row->slug, 0, -4);
                    if ($new === '') {
                        continue;
                    }
                    DB::table('landing_pages')->where('id', $row->id)->update(['slug' => $new]);
                }
            }
        }

        if (Schema::hasTable('marketing_leads')) {
            $leads = DB::table('marketing_leads')
                ->whereNotNull('source_page')
                ->where('source_page', 'like', '%-usa')
                ->orderBy('id')
                ->get(['id', 'source_page']);

            foreach ($leads as $row) {
                $sp = (string) $row->source_page;
                if (! str_ends_with($sp, '-usa')) {
                    continue;
                }
                $new = substr($sp, 0, -4);
                DB::table('marketing_leads')->where('id', $row->id)->update(['source_page' => $new]);
            }
        }

        if (Schema::hasTable('leads')) {
            foreach (['source_page', 'landing_slug'] as $col) {
                if (! Schema::hasColumn('leads', $col)) {
                    continue;
                }
                $rows = DB::table('leads')
                    ->whereNotNull($col)
                    ->where($col, 'like', '%-usa')
                    ->orderBy('id')
                    ->get(['id', $col]);

                foreach ($rows as $row) {
                    $val = (string) $row->{$col};
                    if (! str_ends_with($val, '-usa')) {
                        continue;
                    }
                    DB::table('leads')->where('id', $row->id)->update([$col => substr($val, 0, -4)]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lead_landing_pages')) {
            $rows = DB::table('lead_landing_pages')
                ->where('location_slug', 'usa')
                ->where('slug', 'not like', '%-usa')
                ->orderBy('id')
                ->get(['id', 'slug']);

            foreach ($rows as $row) {
                $slug = (string) $row->slug;
                if ($slug === '' || str_ends_with($slug, '-usa')) {
                    continue;
                }
                DB::table('lead_landing_pages')->where('id', $row->id)->update(['slug' => $slug.'-usa']);
            }
        }

        if (Schema::hasTable('landing_pages') && Schema::hasTable('countries')) {
            $usCountryIds = DB::table('countries')
                ->where(function ($q): void {
                    $q->where('code', 'US')->orWhere('url_slug', 'usa');
                })
                ->pluck('id');

            if ($usCountryIds->isNotEmpty()) {
                $rows = DB::table('landing_pages')
                    ->whereIn('country_id', $usCountryIds)
                    ->where('slug', 'not like', '%-usa')
                    ->orderBy('id')
                    ->get(['id', 'slug']);

                foreach ($rows as $row) {
                    $slug = (string) $row->slug;
                    if ($slug === '' || str_ends_with($slug, '-usa')) {
                        continue;
                    }
                    DB::table('landing_pages')->where('id', $row->id)->update(['slug' => $slug.'-usa']);
                }
            }
        }

    }
};
