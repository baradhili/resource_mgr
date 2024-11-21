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
        Schema::table(\Config::get('teamwork.teams_table'), function (Blueprint $table) {
            $table->string('resource_type')->nullable()->after('name');
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->string('resource_type')->nullable()->after('full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(\Config::get('teamwork.teams_table'), function (Blueprint $table) {
            $table->dropColumn('resource_type');
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('resource_type');
        });
    }
};
