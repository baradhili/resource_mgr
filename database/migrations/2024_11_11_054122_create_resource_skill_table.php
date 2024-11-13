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
            $table->integer('resources_id');
            $table->integer('skills_id')->index('resource_skill_skill_id_foreign');
            $table->enum('proficiency_levels', ['Beginner', 'Intermediate', 'Advanced', 'Expert'])->default('Beginner');
            $table->timestamps();

            $table->primary(['resources_id', 'skills_id']);
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
