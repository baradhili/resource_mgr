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
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->string('record_type'); // 'allocation' or 'demand'
            $table->unsignedBigInteger('record_id');
            $table->string('field');
            $table->decimal('old_value', 10, 3);
            $table->decimal('new_value', 10, 3);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approval_date')->nullable();
            $table->timestamps();
        });

        // Add foreign key constraints
        Schema::table('change_requests', function (Blueprint $table) {
            $table->foreign('requested_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
