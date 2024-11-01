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
        Schema::create('demands', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('year', 4)->nullable();
            $table->integer('month')->nullable();
            $table->decimal('fte', 2, 0)->nullable();
            $table->enum('status', ['Proposed', 'Active', 'Cancelled', 'Completed', 'On Hold', 'Prioritised'])->nullable();
            $table->integer('projects_id')->index('fk_demand_projects1_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};
