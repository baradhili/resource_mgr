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
        Schema::create('allocations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('source', ['Imported', 'Manual'])->nullable()->index();
            $table->date('allocation_date')->nullable();
            $table->decimal('fte', 3)->nullable();
            $table->unsignedBigInteger('resource_id')->nullable()->index('allocations_resource_id_foreign');
            $table->unsignedBigInteger('project_id')->nullable()->index('allocations_project_id_foreign');
            $table->enum('status', ['Proposed', 'Committed'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
