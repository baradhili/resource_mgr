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
        //add new date col
        Schema::table('demands', function (Blueprint $table) {
            $table->date('demand_date')->nullable()->after('id');
        });

        //drop old cols
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //revert changes
        Schema::table('demands', function (Blueprint $table) {
            $table->string('year', 4)->nullable()->after('id');
            $table->integer('month')->nullable()->after('year');
        });
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('demand_date');
        });
    }
};
