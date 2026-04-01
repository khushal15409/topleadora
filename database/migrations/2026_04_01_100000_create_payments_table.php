<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->string('status', 20)->default('success');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['paid_at', 'status']);
            $table->index(['organization_id', 'paid_at']);
        });

        if (Schema::hasTable('subscriptions')) {
            $subs = DB::table('subscriptions')->orderBy('id')->get();
            foreach ($subs as $sub) {
                $plan = DB::table('plans')->where('id', $sub->plan_id)->first();
                if ($plan === null) {
                    continue;
                }
                DB::table('payments')->insert([
                    'organization_id' => $sub->organization_id,
                    'plan_id' => $sub->plan_id,
                    'subscription_id' => $sub->id,
                    'amount' => $plan->price_monthly,
                    'currency' => $plan->currency ?? 'INR',
                    'status' => 'success',
                    'paid_at' => Carbon::parse((string) ($sub->start_date ?? $sub->created_at))->format('Y-m-d H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
