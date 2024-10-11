<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cbos extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description'
    ];

    public function contracts() : HasMany
    {
        return $this->hasMany(Contracts::class, 'cbo_id');
    }
}
