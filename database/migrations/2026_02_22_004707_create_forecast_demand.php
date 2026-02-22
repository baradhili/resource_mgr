<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecast_demands', function (Blueprint $table) {
            // Standard Primary Key (Unsigned BigInt)
            $table->id();

            // CRM Opportunity ID (external system - not a foreign key)
            $table->string('opportunity_id')->nullable()->index();

            // Foreign Key to Users (Updated to match id())
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('stage')->default('discovery')->index();
            $table->string('status')->default('active')->index();

            $table->unsignedTinyInteger('win_probability')
                ->default(50)
                ->comment('0-100 percentage');

            $table->foreignId('client_id')
                ->constrained('clients')
                ->unique()
                ->cascadeOnDelete();

            $table->year('start_year');
            $table->unsignedTinyInteger('start_quarter')->default(1)->index();
            $table->unsignedSmallInteger('duration_months')->default(12);

            $table->decimal('estimated_budget', 14, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Composite Indexes
            $table->index(['start_year', 'start_quarter', 'status']);
            $table->index(['win_probability', 'status']);
            $table->index(['owner_id', 'status', 'start_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_demands');
    }
};