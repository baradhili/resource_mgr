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
        Schema::table('requests', function (Blueprint $table) {
            // Add foreign keys
            $table->foreign(['demand_type_id'])->references(['id'])->on('services')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('product_group_function_domain_id')->references('id')->on('domains')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['funding_approval_stage_id'])->references('id')->on('funding_approval_stages')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            //Drop foreign key constraints
            $table->dropForeign(['demand_type_id']);
            $table->dropForeign(['product_group_function_domain_id']);
            $table->dropForeign(['site_id']);
            $table->dropForeign(['funding_approval_stage_id']);
        });
    }
};
