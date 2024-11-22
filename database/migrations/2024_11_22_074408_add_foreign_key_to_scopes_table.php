<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('scopes', function (Blueprint $table) {
        // Add the foreign key column
        $table->foreignId('estimate_id')
              ->unique() // Ensure only one scope can be linked to an estimate
              ->constrained('estimates')
              ->onDelete('cascade'); // Optional: Cascade delete if the estimate is deleted
    });
}

public function down()
{
    Schema::table('scopes', function (Blueprint $table) {
        $table->dropForeign(['estimate_id']);
        $table->dropColumn('estimate_id');
    });
}
};
