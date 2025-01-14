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
            $table->id();
            $table->date('demand_date');
            $table->double('fte');
            $table->string('status')->nullable();
            $table->string('resource_type')->nullable();
            $table->integer('projects_id')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();

            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('set null');
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
