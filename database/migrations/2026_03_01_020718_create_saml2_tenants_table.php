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
        Schema::create('saml2_tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->char('uuid', 36);
            $table->string('key')->nullable();
            $table->string('idp_entity_id');
            $table->string('idp_login_url');
            $table->string('idp_logout_url');
            $table->text('idp_x509_cert');
            $table->json('metadata');
            $table->timestamps();
            $table->softDeletes();
            $table->string('relay_state_url')->nullable();
            $table->string('name_id_format')->default('persistent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saml2_tenants');
    }
};
