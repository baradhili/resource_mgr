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
        Schema::create('staging_demands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('demand_date');
            $table->double('fte');
            $table->string('status')->nullable();
            $table->string('resource_type')->nullable();
            $table->integer('projects_id')->nullable()->index('staging_demands_projects_id_foreign');
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staging_demands');
    }
};
