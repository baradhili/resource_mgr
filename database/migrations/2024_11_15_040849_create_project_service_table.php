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
            $table->id();
            $table->integer('project_id');
            $table->unsignedBigInteger('service_id');
            $table->integer('quantity')->default(1); // Number of times the service is consumed
            $table->decimal('total_cost', 10, 2)->nullable(); // Total cost for the service
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
