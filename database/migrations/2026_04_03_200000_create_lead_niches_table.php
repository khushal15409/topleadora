<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_niches', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('meta_title');
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('hero_headline');
            $table->text('hero_subheadline')->nullable();
            $table->string('hero_cta')->default('Get free quote');
            $table->string('hero_image_url')->nullable();
            $table->string('hero_image_fallback_url')->nullable();
            $table->string('hero_image_alt')->nullable();
            $table->string('og_image_url')->nullable();
            $table->string('trust_section_image_url')->nullable();
            $table->string('how_section_image_url')->nullable();
            $table->string('trust_badge')->nullable();
            $table->string('rating_label')->nullable();
            $table->json('benefits')->nullable();
            $table->json('how_steps')->nullable();
            $table->json('testimonials')->nullable();
            $table->json('faqs')->nullable();
            $table->string('final_cta_title')->nullable();
            $table->text('final_cta_text')->nullable();
            $table->string('final_cta_button')->nullable();
            $table->text('footer_disclaimer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_niches');
    }
};
