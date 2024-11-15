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
        Schema::table('project_service', function (Blueprint $table) {
            $table->foreign(['project_id'])->references(['id'])->on('projects')->onDelete('cascade');
            $table->foreign(['service_id'])->references(['id'])->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_service', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['service_id']);
        });
    }
};
