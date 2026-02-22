<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->id()->change();

            // Update decimal precision to prevent truncation errors
            $table->decimal('total_cost', 12, 2)->change();

            // Ensure foreign keys are properly typed as Unsigned BigInt
            $table->unsignedBigInteger('created_by')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
            $table->unsignedBigInteger('estimate_owner')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
            $table->decimal('total_cost', 12)->change();
        });
    }
};
