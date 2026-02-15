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
        Schema::create('staging_allocations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('allocation_date');
            $table->double('fte');
            $table->integer('resources_id')->nullable()->index('staging_allocations_resources_id_foreign');
            $table->integer('projects_id')->nullable()->index('staging_allocations_projects_id_foreign');
            $table->string('status')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staging_allocations');
    }
};
