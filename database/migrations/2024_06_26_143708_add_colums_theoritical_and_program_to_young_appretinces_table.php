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
        Schema::table('young_apprentice_data', function (Blueprint $table) {
            $table->string('total_hours_program')->nullable();
            $table->string('period_of_program')->nullable();
            $table->string('period_until_program')->nullable();
            $table->string('total_hours_phase_theoretical')->nullable();
            $table->string('total_hours_theoretical')->nullable();
            $table->string('total_week_hours_theoretical')->nullable();
            $table->string('day_theoretical')->nullable();
            $table->string('hours_phase_theoretical')->nullable();
            $table->string('period_of_theoretical_phase')->nullable();
            $table->string('period_until_theoretical_phase')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('young_apprentice_data', function (Blueprint $table) {
            //
        });
    }
};
