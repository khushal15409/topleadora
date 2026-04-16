<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Avoid false-success if any insert forgets to set status explicitly.
        // Use a direct statement to avoid requiring doctrine/dbal for column change.
        try {
            DB::statement("ALTER TABLE payments ALTER COLUMN status SET DEFAULT 'pending'");
        } catch (\Throwable) {
            // MySQL/MariaDB syntax
            try {
                DB::statement("ALTER TABLE payments MODIFY status VARCHAR(20) NOT NULL DEFAULT 'pending'");
            } catch (\Throwable) {
                // noop: leave default as-is if unsupported (platform-specific)
            }
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE payments ALTER COLUMN status SET DEFAULT 'success'");
        } catch (\Throwable) {
            try {
                DB::statement("ALTER TABLE payments MODIFY status VARCHAR(20) NOT NULL DEFAULT 'success'");
            } catch (\Throwable) {
                // noop
            }
        }
    }
};

