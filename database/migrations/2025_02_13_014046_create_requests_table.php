<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_type_id')->nullable();
            $table->unsignedBigInteger('product_group_function_domain_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->string('business_partner', 255)->nullable();
            $table->string('request_title', 255)->nullable();
            $table->text('background')->nullable();
            $table->text('business_need')->nullable();
            $table->text('problem_statement')->nullable();
            $table->text('specific_requirements')->nullable();
            $table->unsignedBigInteger('funding_approval_stage_id')->nullable();
            $table->string('wbs_number', 255)->nullable();
            $table->date('expected_start')->nullable();
            $table->integer('expected_duration')->nullable();
            $table->text('business_value')->nullable();
            $table->string('business_unit', 255)->nullable();
            $table->string('additional_expert_contact', 255)->nullable();
            $table->longText('attachments')->nullable();
            $table->string('resource_type')->nullable();
            $table->decimal('fte', 3, 2)->nullable();
            $table->enum('status', ['Proposed','Committed','Manual','Closed'])->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
