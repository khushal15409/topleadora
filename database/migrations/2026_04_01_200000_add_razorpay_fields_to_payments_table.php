<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('subscription_id')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('payments', 'gateway')) {
                $table->string('gateway', 30)->default('manual')->after('currency');
            }

            if (! Schema::hasColumn('payments', 'razorpay_order_id')) {
                $table->string('razorpay_order_id', 100)->nullable()->after('gateway');
            }
            if (! Schema::hasColumn('payments', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id', 100)->nullable()->after('razorpay_order_id');
            }
            if (! Schema::hasColumn('payments', 'razorpay_signature')) {
                $table->string('razorpay_signature', 255)->nullable()->after('razorpay_payment_id');
            }
            if (! Schema::hasColumn('payments', 'meta')) {
                $table->json('meta')->nullable()->after('razorpay_signature');
            }

            // Idempotency (nulls allowed).
            $table->unique('razorpay_order_id');
            $table->unique('razorpay_payment_id');
            $table->index(['gateway', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'meta')) {
                $table->dropColumn('meta');
            }
            if (Schema::hasColumn('payments', 'razorpay_signature')) {
                $table->dropColumn('razorpay_signature');
            }
            if (Schema::hasColumn('payments', 'razorpay_payment_id')) {
                $table->dropUnique(['razorpay_payment_id']);
                $table->dropColumn('razorpay_payment_id');
            }
            if (Schema::hasColumn('payments', 'razorpay_order_id')) {
                $table->dropUnique(['razorpay_order_id']);
                $table->dropColumn('razorpay_order_id');
            }
            if (Schema::hasColumn('payments', 'gateway')) {
                $table->dropIndex(['gateway', 'status']);
                $table->dropColumn('gateway');
            }
            if (Schema::hasColumn('payments', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};

