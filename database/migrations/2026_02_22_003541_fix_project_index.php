<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        // change() updates the existing column to BigInt Unsigned
        $table->id()->change(); 
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        // Revert back to signed integer if necessary
        $table->integer('id', true)->change();
    });
}
};
