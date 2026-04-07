<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Programmatic / city landings (e.g. /leads/loan-ahmedabad): multiple rows per service+country
 * with unique slugs. Optional city_slug / city_label for analytics and dynamic copy.
 */
return new class extends Migration
{
    public function up(): void
    {
        // MySQL: composite unique may back FK column indexes — add a plain index first, then drop unique.
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->index(['service_id', 'country_id'], 'landing_pages_service_id_country_id_index');
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropUnique(['service_id', 'country_id']);
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('city_slug', 64)->nullable()->after('slug');
            $table->string('city_label', 128)->nullable()->after('city_slug');
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['city_slug', 'city_label']);
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->unique(['service_id', 'country_id']);
        });

        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropIndex('landing_pages_service_id_country_id_index');
        });
    }
};
