<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('wallet_transactions')) {
            return;
        }

        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('wallet_transactions', 'razorpay_order_id')) {
                $table->string('razorpay_order_id', 100)->nullable()->after('reference_id');
            }
            if (!Schema::hasColumn('wallet_transactions', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id', 100)->nullable()->after('razorpay_order_id');
            }
            if (!Schema::hasColumn('wallet_transactions', 'razorpay_signature')) {
                $table->string('razorpay_signature', 255)->nullable()->after('razorpay_payment_id');
            }
            if (!Schema::hasColumn('wallet_transactions', 'meta')) {
                $table->json('meta')->nullable()->after('razorpay_signature');
            }

            // Idempotency (nulls allowed).
            try {
                $table->unique('razorpay_payment_id');
            } catch (\Throwable) {
                // noop
            }
            try {
                $table->index(['organization_id', 'razorpay_order_id']);
            } catch (\Throwable) {
                // noop
            }
            try {
                $table->index(['organization_id', 'status']);
            } catch (\Throwable) {
                // noop
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('wallet_transactions')) {
            return;
        }

        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_transactions', 'meta')) {
                $table->dropColumn('meta');
            }
            if (Schema::hasColumn('wallet_transactions', 'razorpay_signature')) {
                $table->dropColumn('razorpay_signature');
            }
            if (Schema::hasColumn('wallet_transactions', 'razorpay_payment_id')) {
                try {
                    $table->dropUnique(['razorpay_payment_id']);
                } catch (\Throwable) {
                    // noop
                }
                $table->dropColumn('razorpay_payment_id');
            }
            if (Schema::hasColumn('wallet_transactions', 'razorpay_order_id')) {
                try {
                    $table->dropIndex(['organization_id', 'razorpay_order_id']);
                } catch (\Throwable) {
                    // noop
                }
                $table->dropColumn('razorpay_order_id');
            }

            // status index may remain from other migrations; drop if exists.
            try {
                $table->dropIndex(['organization_id', 'status']);
            } catch (\Throwable) {
                // noop
            }
        });
    }
};

