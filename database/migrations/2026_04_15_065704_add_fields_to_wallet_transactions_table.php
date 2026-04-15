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
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('source')->after('type')->nullable(); // recharge, api_usage
            $table->string('reference_id')->after('source')->nullable(); // payment_id or order_id
            $table->string('status')->after('reference_id')->default('success'); // success, failed, pending
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['source', 'reference_id', 'status']);
        });
    }
};
