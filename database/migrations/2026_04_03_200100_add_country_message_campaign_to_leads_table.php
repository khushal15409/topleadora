<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('country', 120)->nullable()->after('city');
            $table->text('message')->nullable()->after('notes');
            $table->string('campaign', 255)->nullable()->after('source_page');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['country', 'message', 'campaign']);
        });
    }
};
