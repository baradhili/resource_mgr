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
        Schema::create('resource_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('skill_id')->index('resource_skill_skill_id_foreign');
            $table->enum('proficiency_levels', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->default('Beginner');
            $table->timestamps();

            $table->primary(['resource_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_skill');
    }
};
