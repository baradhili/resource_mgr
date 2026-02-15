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
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('quantity')->nullable();
            $table->decimal('price')->nullable()->default(0);
            $table->boolean('obligatory')->default(false);
            $table->unsignedSmallInteger('position')->default(0);
            $table->unsignedBigInteger('section_id')->index('items_section_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
