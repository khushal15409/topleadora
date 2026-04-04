<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_key')->unique();
            $table->string('label');
            $table->string('field_type', 32)->default('text')->comment('text, textarea, email');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_form_fields');
    }
};
