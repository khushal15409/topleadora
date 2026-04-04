<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 64);
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->foreignId('country_id')->constrained('countries')->restrictOnDelete();
            $table->string('country_code', 2);
            $table->string('country_name', 128);
            $table->foreignId('landing_page_id')->nullable()->constrained('landing_pages')->nullOnDelete();
            $table->string('source_page')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->json('extra')->nullable()->comment('Dynamic marketing form field values');
            $table->timestamps();

            $table->index(['service_id', 'created_at']);
            $table->index(['country_id', 'created_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_leads');
    }
};
