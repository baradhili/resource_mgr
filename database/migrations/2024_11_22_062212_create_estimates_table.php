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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('use_name_as_title');
            $table->date('expiration_date');
            $table->string('currency_symbol');
            $table->string('currency_decimal_separator');
            $table->string('currency_thousands_separator');
            $table->boolean('allows_to_select_items');
            $table->string('tags');
            $table->float('total_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
