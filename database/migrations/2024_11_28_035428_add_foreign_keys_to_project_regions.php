<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_regions', function (Blueprint $table) {
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreign(['project_id'])->references(['id'])->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_regions', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['region_id']);
            $table->dropColumn(['project_id']);
            $table->dropColumn(['region_id']);
        });
    }
};
