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
            $table->string('working_day')->nullable();
            $table->string('work_days_select')->nullable();
            $table->string('work_schedule')->nullable();
            $table->string('program_total_contract')->nullable();
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
