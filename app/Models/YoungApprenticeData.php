<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YoungApprenticeData extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "date_of_birth",
        "mother_name",
        "father_name",
        "gener",
        "marital_status",
        "document_rg",
        "document_cpf",
        "phone_number",
        "cellphone_number",
        "work_card",
        "series_work_card",
        "registration_date",
        "education",
        "has_course",
        "course_name",
        "school_name",
        "shift_course",
        "university_education",
        "university_education_name",
        "address_city",
        "address",
        "address_zipcode",
        "has_enlist",
        "has_army_reservist",
        "army_reservist_number",
        "has_informatics_knowledge",
        "has_disability",
        "working_day",
        "work_days_select",
        "work_schedule",
        "program_total_contract",
        "total_hours_program",
        "period_of_program",
        "period_until_program",
        "total_hours_phase_theoretical",
        "total_hours_theoretical",
        "total_week_hours_theoretical",
        "day_theoretical",
        "hours_phase_theoretical",
        "period_of_theoretical_phase",
        "period_until_theoretical_phase",
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
