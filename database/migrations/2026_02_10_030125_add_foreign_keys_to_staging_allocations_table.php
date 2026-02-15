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
        Schema::table('staging_allocations', function (Blueprint $table) {
            $table->foreign(['projects_id'])->references(['id'])->on('projects')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['resources_id'])->references(['id'])->on('resources')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staging_allocations', function (Blueprint $table) {
            $table->dropForeign('staging_allocations_projects_id_foreign');
            $table->dropForeign('staging_allocations_resources_id_foreign');
        });
    }
};
