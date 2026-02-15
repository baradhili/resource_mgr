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
        Schema::table('team_user', function (Blueprint $table) {
            $table->foreign(['team_id'])->references(['id'])->on('teams')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_user', function (Blueprint $table) {
            $table->dropForeign('team_user_team_id_foreign');
            $table->dropForeign('team_user_user_id_foreign');
        });
    }
};
