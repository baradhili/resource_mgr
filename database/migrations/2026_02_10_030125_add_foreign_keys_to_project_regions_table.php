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
        Schema::table('project_regions', function (Blueprint $table) {
            $table->foreign(['region_id'])->references(['id'])->on('regions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_regions', function (Blueprint $table) {
            $table->dropForeign('project_regions_region_id_foreign');
        });
    }
};
