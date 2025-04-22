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
        Schema::table('skills', function (Blueprint $table) {
            $table->string('context')->nullable();
            $table->json('employers')->nullable();
            $table->json('keywords')->nullable();
            $table->string('category')->nullable();
            $table->json('certifications')->nullable();
            $table->json('occupations')->nullable();
            $table->string('license')->nullable();
            $table->json('derived_from')->nullable();
            $table->string('source_id')->nullable();
            $table->string('type')->nullable();
            $table->string('authors')->nullable();
            $table->dropColumn('sfia_code');
            $table->dropColumn('sfia_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('context');
            $table->dropColumn('employers');
            $table->dropColumn('keywords');
            $table->dropColumn('category');
            $table->dropColumn('certifications');
            $table->dropColumn('occupations');
            $table->dropColumn('license');
            $table->dropColumn('derived_from');
            $table->dropColumn('source_id');
            $table->dropColumn('type');
            $table->dropColumn('authors');
            $table->string('sfia_code')->nullable();
            $table->integer('sfia_level')->nullable();
        });
    }
};
