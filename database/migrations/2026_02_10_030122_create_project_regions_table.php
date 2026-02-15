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
        Schema::create('project_regions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->index('project_regions_project_id_foreign');
            $table->timestamps();
            $table->unsignedBigInteger('region_id')->index('project_regions_region_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_regions');
    }
};
