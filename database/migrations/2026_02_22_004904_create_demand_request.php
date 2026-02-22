<?php

declare(strict_types=1);

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
            // Primary Key (Standard Auto-incrementing BigInt)
            $table->id();

            // ------------------------------------------------------------------
            // Foreign Keys 
            // ------------------------------------------------------------------
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();

            $table->foreignId('requester_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('forecast_demand_id')
                ->nullable()
                ->constrained('forecast_demands')
                ->nullOnDelete();

            $table->foreignId('allocated_resource_id')
                ->nullable()
                ->constrained('resources')
                ->nullOnDelete();

            $table->foreignId('funded_estimate_id')
                ->nullable()
                ->constrained('estimates')
                ->nullOnDelete();

            $table->foreignId('funded_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // ------------------------------------------------------------------
            // Status & Attributes
            // ------------------------------------------------------------------
            $table->string('status')
                ->default('pending_estimate')
                ->index();

            $table->string('priority')
                ->default('medium')
                ->index();

            $table->string('role_title');

            $table->decimal('quantity_fte', 8, 2)
                ->default(1.00);

            // ------------------------------------------------------------------
            // Date Fields
            // ------------------------------------------------------------------
            $table->date('start_date')->index();
            $table->date('end_date');
            $table->timestamp('filled_at')->nullable();

            // ------------------------------------------------------------------
            // Funding Fields
            // ------------------------------------------------------------------
            $table->boolean('is_funded')
                ->default(false)
                ->index();

            $table->string('funding_source')->nullable();
            $table->string('budget_code')->nullable();
            $table->decimal('approved_budget_amount', 12, 2)->nullable();

            // ------------------------------------------------------------------
            // Timestamps
            // ------------------------------------------------------------------
            $table->timestamps();

            // ------------------------------------------------------------------
            // Composite Indexes
            // ------------------------------------------------------------------
            $table->index(['status', 'is_funded']); 
            $table->index(['priority', 'start_date']);
            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demand_requests');
    }
};