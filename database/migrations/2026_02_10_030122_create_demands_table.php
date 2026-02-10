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
        Schema::create('demands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->index('demands_client_id_foreign');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->dateTime('expected_start_date')->nullable();
            $table->dateTime('expected_end_date')->nullable();
            $table->decimal('fte', 3)->nullable()->comment('FTE requested');
            $table->string('status')->default('new')->comment('Sales funnel stage');
            $table->text('source')->nullable()->comment('Original source of demand');
            $table->text('notes')->nullable()->comment('Additional structured data from imports');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};
