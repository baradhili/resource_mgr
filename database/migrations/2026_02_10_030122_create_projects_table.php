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
            $table->unsignedBigInteger('client_id')->nullable()->index('projects_client_id_foreign');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('empowerID', 20)->nullable();
            $table->string('name')->nullable();
            $table->string('projectManager', 45)->nullable();
            $table->timestamps();
            $table->enum('status', ['Proposed', 'Active', 'Cancelled', 'Completed', 'On Hold', 'Prioritised'])->nullable();
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
