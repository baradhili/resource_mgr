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
            $table->foreign(['resources_id'])->references(['id'])->on('resources')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['skills_id'])->references(['id'])->on('skills')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_skill', function (Blueprint $table) {
            $table->dropForeign('resource_skill_resources_id_foreign');
            $table->dropForeign('resource_skill_skills_id_foreign');
        });
    }
};
