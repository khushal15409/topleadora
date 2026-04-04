<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_niche_id')->constrained('lead_niches')->cascadeOnDelete();
            $table->string('slug')->unique()->comment('URL segment: US = niche slug only; others niche-location e.g. loan-india');
            $table->string('location_slug', 64);
            $table->string('location_label', 128);
            $table->string('meta_title');
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('robots_meta')->default('index,follow');
            $table->string('hero_headline_override')->nullable();
            $table->text('hero_subheadline_override')->nullable();
            $table->longText('seo_body')->nullable()->comment('Long-form HTML for rankings');
            $table->decimal('aggregate_rating_value', 2, 1)->nullable();
            $table->unsignedInteger('aggregate_rating_count')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['lead_niche_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_landing_pages');
    }
};
