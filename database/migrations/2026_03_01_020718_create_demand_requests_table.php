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
        Schema::create('demand_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('requester_id')->index('demand_requests_requester_id_foreign');
            $table->unsignedBigInteger('forecast_demand_id')->nullable()->index('demand_requests_forecast_demand_id_foreign');
            $table->unsignedBigInteger('allocated_resource_id')->nullable()->index('demand_requests_allocated_resource_id_foreign');
            $table->unsignedBigInteger('funded_estimate_id')->nullable()->index('demand_requests_funded_estimate_id_foreign');
            $table->unsignedBigInteger('funded_by_id')->nullable()->index('demand_requests_funded_by_id_foreign');
            $table->unsignedBigInteger('client_id')->index('demand_requests_client_id_foreign');
            $table->string('status')->default('pending_estimate');
            $table->string('priority')->default('medium');
            $table->string('role_title');
            $table->decimal('quantity_fte', 3)->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('filled_at')->nullable();
            $table->boolean('is_funded')->default(false)->index();
            $table->string('funding_source')->nullable();
            $table->string('budget_code')->nullable();
            $table->decimal('approved_budget_amount', 12)->nullable();
            $table->timestamps();

            $table->index(['priority', 'start_date']);
            $table->index(['project_id', 'status']);
            $table->index(['status', 'is_funded']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demand_requests');
    }
};
