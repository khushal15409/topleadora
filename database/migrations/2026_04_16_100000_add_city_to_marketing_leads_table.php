<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('marketing_leads')) {
            return;
        }

        if (Schema::hasColumn('marketing_leads', 'city')) {
            return;
        }

        Schema::table('marketing_leads', function (Blueprint $table) {
            $table->string('city', 128)->nullable()->after('country_name');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('marketing_leads') || ! Schema::hasColumn('marketing_leads', 'city')) {
            return;
        }

        Schema::table('marketing_leads', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
};
