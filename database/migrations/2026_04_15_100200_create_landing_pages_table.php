<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('meta_title');
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('robots_meta')->default('index,follow');
            $table->longText('seo_body')->nullable();
            $table->json('content_json')->nullable()->comment('Hero, benefits, faqs, sections for Blade');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['service_id', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
