<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contracts extends Model
{
    use HasFactory;

    protected $fillable = [
        'young_apprentice_id',
        'company_id',
        'cbo_id',
        'contract_number',
        'description',
        'period_of',
        'period_until',
    ];

    public function young_apprentice() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cbo() : belongsTo
    {
        return $this->belongsTo(Cbos::class);
    }
}
