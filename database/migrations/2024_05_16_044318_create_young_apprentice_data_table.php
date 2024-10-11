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
        Schema::create('young_apprentice_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('date_of_birth')->nullable();
            $table->string('mother_name')->nullable();
            $table->enum('gener', ['Masculino', 'Feminino', 'Indefinido'])->default('Indefinido')->nullable();
            $table->enum('marital_status', ['Solteiro', 'Casado', 'Divorciado', 'Viuvo', 'Indefinido'])->default('Indefinido');
            $table->string('document_rg')->nullable();
            $table->string('document_cpf')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('cellphone_number')->nullable();
            $table->string('work_card')->nullable();
            $table->string('series_work_card')->nullable();
            $table->string('registration_date')->nullable();
            $table->enum('education', ['Ensino Medio Incompleto', 'Ensino Medio Completo'])->default('Ensino Medio Incompleto')->nullable();
            $table->enum('has_course', ['Sim', 'Nao'])->default('Nao')->nullable();
            $table->string('course_name')->nullable();
            $table->string('school_name')->nullable();
            $table->enum('shift_course', ['Manha', 'Tarde', 'Noite'])->default('Manha')->nullable();
            $table->enum('university_education', ['Sim', 'Nao'])->default('Nao')->nullable();
            $table->string('university_education_name')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address')->nullable();
            $table->string('address_zipcode')->nullable();
            $table->enum('has_enlist', ['Sim', 'Nao'])->default('Nao')->nullable();
            $table->enum('has_army_reservist', ['Sim', 'Nao'])->default('Nao')->nullable();
            $table->string('army_reservist_number')->nullable();
            $table->enum('has_informatics_knowledge', ['Nao', 'Basico', 'Intermediario', 'Avancado'])->default('Nao')->nullable();
            $table->enum('has_disability', ['Sim', 'Nao'])->default('Nao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('young_apprentice_data');
    }
};
