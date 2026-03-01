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
        Schema::table('resource_skill', function (Blueprint $table) {
            $table->foreign(['skill_id'])->references(['id'])->on('skills')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_skill', function (Blueprint $table) {
            $table->dropForeign('resource_skill_skill_id_foreign');
        });
    }
};
