<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('type'); // credit, debit
            $table->string('source')->nullable(); // recharge, api_usage
            $table->string('reference_id')->nullable(); // payment_id or order_id / reservation ref
            $table->string('status')->default('success'); // success, failed, pending
            $table->string('razorpay_order_id', 100)->nullable();
            $table->string('razorpay_payment_id', 100)->nullable();
            $table->string('razorpay_signature', 255)->nullable();
            $table->json('meta')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            // Indexes/constraints (nulls allowed).
            $table->unique('razorpay_payment_id');
            $table->index(['organization_id', 'razorpay_order_id']);
            $table->index(['organization_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
