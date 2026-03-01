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
        Schema::table('change_requests', function (Blueprint $table) {
            $table->foreign(['approved_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['requested_by'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropForeign('change_requests_approved_by_foreign');
            $table->dropForeign('change_requests_requested_by_foreign');
        });
    }
};
