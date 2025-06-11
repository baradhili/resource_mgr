<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table("change_requests", function (Blueprint $table) {
            $table->json("old_value")->nullable()->change();
            $table->json("new_value")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table("change_requests", function (Blueprint $table) {
            $table->decimal("old_value", 10, 3)->nullable(false)->change();
            $table->decimal("new_value", 10, 3)->nullable(false)->change();
        });
    }
};
