<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500);
            $table->text('query_string')->nullable();
            $table->string('route_name', 191)->nullable();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->string('session_id', 191)->nullable();
            $table->boolean('is_bot')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index('created_at');
            $table->index(['created_at', 'is_bot']);
            $table->index('path', 'site_visits_path_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
