<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('city', 128)->nullable()->after('phone');
            $table->string('niche', 64)->nullable()->after('city');
            $table->string('source_page', 128)->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['city', 'niche', 'source_page']);
        });
    }
};
