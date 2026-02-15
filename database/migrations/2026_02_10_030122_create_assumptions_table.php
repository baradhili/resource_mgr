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
        Schema::create('assumptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('impact');
            $table->timestamps();
            $table->unsignedBigInteger('estimate_id')->index('assumptions_estimate_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assumptions');
    }
};
