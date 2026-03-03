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
        Schema::table('resources', function (Blueprint $table) {
            $table->foreign(['location_id'])->references(['id'])->on('locations')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['region_id'])->references(['id'])->on('regions')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['resource_type'])->references(['id'])->on('resource_types')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign('resources_location_id_foreign');
            $table->dropForeign('resources_region_id_foreign');
            $table->dropForeign('resources_resource_type_foreign');
            $table->dropForeign('resources_user_id_foreign');
        });
    }
};
