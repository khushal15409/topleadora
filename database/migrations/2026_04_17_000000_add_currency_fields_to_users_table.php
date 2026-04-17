<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'currency_code')) {
                $table->string('currency_code', 3)->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 2)->nullable()->after('currency_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'country_code')) {
                $table->dropColumn('country_code');
            }
            if (Schema::hasColumn('users', 'currency_code')) {
                $table->dropColumn('currency_code');
            }
        });
    }
};

