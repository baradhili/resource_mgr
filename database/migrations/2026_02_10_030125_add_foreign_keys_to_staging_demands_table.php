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
        Schema::table('staging_demands', function (Blueprint $table) {
            $table->foreign(['projects_id'])->references(['id'])->on('projects')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staging_demands', function (Blueprint $table) {
            $table->dropForeign('staging_demands_projects_id_foreign');
        });
    }
};
