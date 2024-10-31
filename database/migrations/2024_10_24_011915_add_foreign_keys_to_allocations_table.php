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
        Schema::table('allocations', function (Blueprint $table) {
            $table->foreign(['projects_id'], 'fk_allocations_projects1')->references(['id'])->on('projects')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['resources_id'], 'fk_allocations_resources1')->references(['id'])->on('resources')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropForeign('fk_allocations_projects1');
            $table->dropForeign('fk_allocations_resources1');
        });
    }
};
