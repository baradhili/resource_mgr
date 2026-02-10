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
        Schema::create('resources', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('full_name')->nullable();
            $table->string('empowerID')->nullable()->unique('empowerid_unique');
            $table->timestamps();
            $table->double('baseAvailability')->nullable()->default(1);
            $table->unsignedBigInteger('region_id')->nullable()->index('resources_region_id_foreign');
            $table->unsignedBigInteger('location_id')->nullable()->index('resources_location_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('resources_user_id_foreign');
            $table->unsignedBigInteger('resource_type')->nullable()->index('resources_resource_type_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
