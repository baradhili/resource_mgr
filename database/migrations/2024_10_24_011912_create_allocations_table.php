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
        Schema::create('allocations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('year', 4)->nullable();
            $table->integer('month')->nullable();
            $table->decimal('fte', 2, 0)->nullable();
            $table->integer('resources_id')->index('fk_allocations_resources1_idx');
            $table->integer('projects_id')->index('fk_allocations_projects1_idx');
            $table->enum('status', ['Proposed', 'Committed'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
