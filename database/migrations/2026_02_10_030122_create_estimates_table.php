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
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('use_name_as_title')->default(true);
            $table->date('expiration_date');
            $table->string('currency_symbol')->default('$');
            $table->string('currency_decimal_separator')->default('.');
            $table->string('currency_thousands_separator')->default(',');
            $table->boolean('allows_to_select_items')->default(true);
            $table->string('tags')->nullable();
            $table->decimal('total_cost', 12);
            $table->unsignedBigInteger('client_id')->nullable()->index('estimates_client_id_foreign');
            $table->unsignedBigInteger('terms_and_conditions_id')->nullable()->unique();
            $table->unsignedBigInteger('created_by')->nullable()->index('estimates_created_by_foreign');
            $table->unsignedBigInteger('updated_by')->nullable()->index('estimates_updated_by_foreign');
            $table->unsignedBigInteger('estimate_owner')->nullable()->index('estimates_estimate_owner_foreign');
            $table->unsignedBigInteger('partner')->nullable()->index('estimates_partner_foreign');
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
