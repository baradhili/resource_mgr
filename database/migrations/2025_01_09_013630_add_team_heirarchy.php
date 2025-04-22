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
        // drop invites
        Schema::dropIfExists(\Config::get('teamwork.team_invites_table'));

        // add recursive ref
        Schema::table(\Config::get('teamwork.teams_table'), function (Blueprint $table) {
            $table->unsignedInteger('parent_team_id')->nullable();
        });

        // and key
        Schema::table(\Config::get('teamwork.teams_table'), function (Blueprint $table) {
            $table->index('parent_team_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //  add invites back in
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

        // drop recursive
        Schema::table(\Config::get('teamwork.teams_table'), function (Blueprint $table) {
            $table->dropIndex(['parent_team_id']);
            $table->dropColumn('parent_team_id');
        });

    }
};
