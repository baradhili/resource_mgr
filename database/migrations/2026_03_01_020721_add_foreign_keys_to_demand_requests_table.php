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
        Schema::table('demand_requests', function (Blueprint $table) {
            $table->foreign(['allocated_resource_id'])->references(['id'])->on('resources')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['client_id'])->references(['id'])->on('clients')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['forecast_demand_id'])->references(['id'])->on('forecast_demands')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['funded_by_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['funded_estimate_id'])->references(['id'])->on('estimates')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['project_id'])->references(['id'])->on('projects')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['requester_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demand_requests', function (Blueprint $table) {
            $table->dropForeign('demand_requests_allocated_resource_id_foreign');
            $table->dropForeign('demand_requests_client_id_foreign');
            $table->dropForeign('demand_requests_forecast_demand_id_foreign');
            $table->dropForeign('demand_requests_funded_by_id_foreign');
            $table->dropForeign('demand_requests_funded_estimate_id_foreign');
            $table->dropForeign('demand_requests_project_id_foreign');
            $table->dropForeign('demand_requests_requester_id_foreign');
        });
    }
};
