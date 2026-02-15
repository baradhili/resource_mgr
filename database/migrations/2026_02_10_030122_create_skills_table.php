<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('skill_name')->unique();
            $table->text('skill_description')->nullable();
            $table->timestamps();
            $table->string('context')->nullable();
            $table->json('employers')->nullable();
            $table->json('keywords')->nullable();
            $table->string('category')->nullable();
            $table->json('certifications')->nullable();
            $table->json('occupations')->nullable();
            $table->string('license')->nullable();
            $table->json('derived_from')->nullable();
            $table->string('source_id')->nullable();
            $table->string('type')->nullable();
            $table->string('authors')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
