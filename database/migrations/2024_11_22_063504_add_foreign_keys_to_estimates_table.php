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
        Schema::table('estimates', function (Blueprint $table) {
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('estimate_owner')->constrained('users')->onDelete('cascade');
            $table->foreignId('partner')->constrained('users')->onDelete('cascade');
            $table->foreignId('terms_and_conditions_id')->unique()->constrained('terms_and_conditions')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('estimate_owner');
            $table->dropConstrainedForeignId('partner');
            $table->dropConstrainedForeignId('terms_and_conditions_id');
            $table->dropConstrainedForeignId('client_id');
        });
    }
};
