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
        Schema::create('company_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name_legal_representative');
            $table->string('name_youth_supervisor');
            $table->string('phone_youth_supervisor');
            $table->string('email_youth_supervisor');
            $table->string('fantasy_name_company');
            $table->string('social_reason_company');
            $table->string('cnpj_company');
            $table->string('code_company');
            $table->string('state_city_company');
            $table->string('address_company');
            $table->string('address_zipcode_company');
            $table->string('phone_company');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_data');
    }
};
