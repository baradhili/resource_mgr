<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->renameColumn('resource_type', 'resource_type_temp');
        });
        Schema::table('resources', function (Blueprint $table) {

            $table->unsignedBigInteger('resource_type')->nullable();
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->foreign('resource_type')->references('id')->on('resource_types')->onDelete('set null');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('resource_type', 'resource_type_temp');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('resource_type')->nullable();
        });
        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('resource_type')->references('id')->on('resource_types')->onDelete('set null');
        });

        // Insert existing resource types into resource_types table
        $resourceTypes = DB::table('resources')->distinct()->pluck('resource_type_temp')->toArray();
        $teamResourceTypes = DB::table('teams')->distinct()->pluck('resource_type_temp')->toArray();

        // Map resource_type names to IDs
        $resourceTypeMap = DB::table('resource_types')->pluck('id', 'name')->toArray();

        // Update resources table
        foreach ($resourceTypes as $resourceTypeName) {
            $resourceTypeId = (int) $resourceTypeName;
            DB::table('resources')
                ->where('resource_type_temp', $resourceTypeName)
                ->update(['resource_type' => $resourceTypeId]);
        }

        // Update teams table
        foreach ($teamResourceTypes as $resourceTypeName) {
            $resourceTypeId = $resourceTypeMap[$resourceTypeName];
            DB::table('teams')
                ->where('resource_type_temp', $resourceTypeName)
                ->update(['resource_type' => $resourceTypeId]);
        }

        // Drop the temporary resource_type_temp columns
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('resource_type_temp');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('resource_type_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Add the temporary resource_type_temp columns back
        Schema::table('resources', function (Blueprint $table) {
            $table->string('resource_type_temp')->nullable();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->string('resource_type_temp')->nullable();
        });

        // Migrate data back
        $resourceTypeMap = DB::table('resource_types')->pluck('name', 'id')->toArray();

        // Update resources table
        DB::table('resources')->get()->each(function ($resource) use ($resourceTypeMap) {
            DB::table('resources')
                ->where('id', $resource->id)
                ->update(['resource_type_temp' => (string) $resourceTypeMap[$resource->resource_type]]);
        });

        // Update teams table
        DB::table('teams')->get()->each(function ($team) use ($resourceTypeMap) {
            DB::table('teams')
                ->where('id', $team->id)
                ->update(['resource_type_temp' => $resourceTypeMap[$team->resource_type]]);
        });

        // Drop the resource_type columns
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['resource_type']);
            $table->dropColumn('resource_type');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['resource_type']);
            $table->dropColumn('resource_type');
        });

        // Rename the temporary columns back to resource_type
        Schema::table('resources', function (Blueprint $table) {
            $table->renameColumn('resource_type_temp', 'resource_type');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('resource_type_temp', 'resource_type');
        });
    }
};
