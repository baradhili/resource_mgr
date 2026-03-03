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
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('owner_id')->nullable()->index('teams_owner_id_foreign');
            $table->string('name');
            $table->timestamps();
            $table->unsignedBigInteger('parent_team_id')->nullable()->index();
            $table->unsignedBigInteger('resource_type')->nullable()->index('teams_resource_type_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
