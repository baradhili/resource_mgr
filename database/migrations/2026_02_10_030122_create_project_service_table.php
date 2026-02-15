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
        Schema::create('project_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->index('project_service_project_id_foreign');
            $table->unsignedBigInteger('service_id')->index('project_service_service_id_foreign');
            $table->integer('quantity')->default(1);
            $table->decimal('total_cost', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_service');
    }
};
