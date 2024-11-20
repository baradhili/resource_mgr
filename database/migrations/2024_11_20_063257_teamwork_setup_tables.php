<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class TeamworkSetupTables extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(\Config::get('teamwork.users_table'), function (Blueprint $table) {
            $table->integer('current_team_id')->unsigned()->nullable();
        });

        Schema::create(\Config::get('teamwork.teams_table'), function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('owner_id')->unsigned()->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create(\Config::get('teamwork.team_user_table'), function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->timestamps();
        });

        Schema::create(\Config::get('teamwork.team_invites_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->enum('type', ['invite', 'request']);
            $table->string('email');
            $table->string('accept_token');
            $table->string('deny_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(\Config::get('teamwork.users_table'), function (Blueprint $table) {
            $table->dropColumn('current_team_id');
        });

        Schema::dropIfExists(\Config::get('teamwork.team_invites_table'));
        Schema::dropIfExists(\Config::get('teamwork.team_user_table'));
        Schema::dropIfExists(\Config::get('teamwork.teams_table'));
    }
}
