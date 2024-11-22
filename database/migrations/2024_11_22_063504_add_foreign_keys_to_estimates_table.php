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
        Schema::table('estimates', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropForeign('estimates_created_by_foreign');
            $table->dropForeign('estimates_updated_by_foreign');
        });
    }
};
