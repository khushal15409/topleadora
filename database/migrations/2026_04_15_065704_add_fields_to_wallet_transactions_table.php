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
        if (! Schema::hasTable('wallet_transactions')) {
            return;
        }

        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('wallet_transactions', 'source')) {
                $table->string('source')->after('type')->nullable(); // recharge, api_usage
            }
            if (! Schema::hasColumn('wallet_transactions', 'reference_id')) {
                $table->string('reference_id')->after('source')->nullable(); // payment_id or order_id
            }
            if (! Schema::hasColumn('wallet_transactions', 'status')) {
                $table->string('status')->after('reference_id')->default('success'); // success, failed, pending
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('wallet_transactions')) {
            return;
        }

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $drops = [];
            foreach (['source', 'reference_id', 'status'] as $col) {
                if (Schema::hasColumn('wallet_transactions', $col)) {
                    $drops[] = $col;
                }
            }
            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
