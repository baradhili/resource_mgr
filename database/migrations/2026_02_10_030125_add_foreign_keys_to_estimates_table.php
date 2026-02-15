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
        Schema::table('estimates', function (Blueprint $table) {
            $table->foreign(['client_id'])->references(['id'])->on('clients')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['estimate_owner'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['partner'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['terms_and_conditions_id'])->references(['id'])->on('terms_and_conditions')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['updated_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropForeign('estimates_client_id_foreign');
            $table->dropForeign('estimates_created_by_foreign');
            $table->dropForeign('estimates_estimate_owner_foreign');
            $table->dropForeign('estimates_partner_foreign');
            $table->dropForeign('estimates_terms_and_conditions_id_foreign');
            $table->dropForeign('estimates_updated_by_foreign');
        });
    }
};
