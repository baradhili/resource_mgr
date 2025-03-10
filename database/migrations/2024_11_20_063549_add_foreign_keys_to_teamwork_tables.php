<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(\Config::get('teamwork.team_user_table'), function (Blueprint $table) {
            $table->foreign('user_id')
                ->references(\Config::get('teamwork.user_foreign_key'))
                ->on(\Config::get('teamwork.users_table'))
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('team_id')
                ->references('id')
                ->on(\Config::get('teamwork.teams_table'))
                ->onDelete('cascade');
        });

        Schema::table(\Config::get('teamwork.team_invites_table'), function (Blueprint $table) {
            $table->foreign('team_id')
                ->references('id')
                ->on(\Config::get('teamwork.teams_table'))
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(\Config::get('teamwork.team_user_table'), function (Blueprint $table) {
                $table->dropForeign(\Config::get('teamwork.team_user_table').'_user_id_foreign');
                $table->dropForeign(\Config::get('teamwork.team_user_table').'_team_id_foreign');
        });

        Schema::table(\Config::get('teamwork.team_invites_table'), function (Blueprint $table) {
                $table->dropForeign(\Config::get('teamwork.team_invites_table').'_team_id_foreign');
        });
    }
};
