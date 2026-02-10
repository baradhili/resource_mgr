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
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demand_type_id')->nullable()->index('requests_demand_type_id_foreign');
            $table->unsignedBigInteger('product_group_function_domain_id')->nullable()->index('requests_product_group_function_domain_id_foreign');
            $table->unsignedBigInteger('site_id')->nullable()->index('requests_site_id_foreign');
            $table->string('business_partner')->nullable();
            $table->string('request_title')->nullable();
            $table->text('background')->nullable();
            $table->text('business_need')->nullable();
            $table->text('problem_statement')->nullable();
            $table->text('specific_requirements')->nullable();
            $table->unsignedBigInteger('funding_approval_stage_id')->nullable()->index('requests_funding_approval_stage_id_foreign');
            $table->string('wbs_number')->nullable();
            $table->date('expected_start')->nullable();
            $table->integer('expected_duration')->nullable();
            $table->text('business_value')->nullable();
            $table->string('business_unit')->nullable();
            $table->string('additional_expert_contact')->nullable();
            $table->longText('attachments')->nullable();
            $table->string('resource_type')->nullable();
            $table->decimal('fte', 3)->nullable();
            $table->enum('status', ['Proposed', 'Committed', 'Manual', 'Closed'])->nullable();
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
