<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('broadcasts')) {
            return;
        }

        Schema::table('broadcasts', function (Blueprint $table) {
            if (! Schema::hasColumn('broadcasts', 'failed_count')) {
                $table->unsignedInteger('failed_count')->default(0)->after('sent_count');
            }
            if (! Schema::hasColumn('broadcasts', 'last_error')) {
                $table->string('last_error', 500)->nullable()->after('failed_count');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('broadcasts')) {
            return;
        }

        Schema::table('broadcasts', function (Blueprint $table) {
            if (Schema::hasColumn('broadcasts', 'last_error')) {
                $table->dropColumn('last_error');
            }
            if (Schema::hasColumn('broadcasts', 'failed_count')) {
                $table->dropColumn('failed_count');
            }
        });
    }
};

