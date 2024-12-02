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
        Schema::create('projects', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('empowerID', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('projectManager', 45)->nullable();
            $table->enum('status', ['Proposed', 'Active', 'Cancelled', 'Completed', 'On Hold', 'Prioritised'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
