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
        Schema::table('assumptions', function (Blueprint $table) {
            $table->unsignedBigInteger('estimate_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assumptions', function (Blueprint $table) {
            $table->dropForeign(['estimate_id']);
            $table->dropColumn('estimate_id');            
        });
    }
};
