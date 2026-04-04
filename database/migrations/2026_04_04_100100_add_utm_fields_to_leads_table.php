<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('utm_source', 255)->nullable()->after('campaign');
            $table->string('utm_medium', 255)->nullable()->after('utm_source');
            $table->string('landing_slug', 128)->nullable()->after('utm_medium');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['utm_source', 'utm_medium', 'landing_slug']);
        });
    }
};
