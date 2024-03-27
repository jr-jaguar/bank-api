<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'iso',
        'title',
    ];

    public function exchangeRates(): hasMany
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
