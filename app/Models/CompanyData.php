<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyData extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name_legal_representative",
        "name_youth_supervisor",
        "phone_youth_supervisor",
        "email_youth_supervisor",
        "fantasy_name_company",
        "social_reason_company",
        "cnpj_company",
        "code_company",
        "state_city_company",
        "address_company",
        "address_zipcode_company",
        "phone_company"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
