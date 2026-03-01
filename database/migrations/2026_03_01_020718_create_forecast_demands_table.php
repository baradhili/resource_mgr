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
        Schema::create('forecast_demands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('opportunity_id')->nullable()->index();
            $table->unsignedBigInteger('owner_id');
            $table->string('stage')->default('discovery')->index();
            $table->string('status')->default('active')->index();
            $table->unsignedTinyInteger('win_probability')->default(50)->comment('0-100 percentage');
            $table->unsignedBigInteger('client_id')->index('forecast_demands_client_id_foreign');
            $table->year('start_year');
            $table->unsignedTinyInteger('start_quarter')->default(1)->index();
            $table->unsignedSmallInteger('duration_months')->default(12);
            $table->decimal('estimated_budget', 14)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['owner_id', 'status', 'start_year']);
            $table->index(['start_year', 'start_quarter', 'status']);
            $table->index(['win_probability', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecast_demands');
    }
};
