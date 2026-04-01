<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 32)->default('active');
            $table->timestamps();

            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'end_date']);
        });

        if (Schema::hasTable('organizations')) {
            $orgs = DB::table('organizations')->whereNotNull('plan_id')->get();
            foreach ($orgs as $org) {
                DB::table('subscriptions')->insert([
                    'organization_id' => $org->id,
                    'plan_id' => $org->plan_id,
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(30)->toDateString(),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
