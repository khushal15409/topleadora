<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('next_followup_at')->nullable()->after('notes');
            $table->timestamp('followup_completed_at')->nullable()->after('next_followup_at');
            $table->index(['organization_id', 'next_followup_at']);
        });

        DB::table('leads')->where('status', 'qualified')->update(['status' => 'interested']);
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['organization_id', 'next_followup_at']);
            $table->dropColumn(['next_followup_at', 'followup_completed_at']);
        });
    }
};
