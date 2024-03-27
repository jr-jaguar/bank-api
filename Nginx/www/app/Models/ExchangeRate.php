<?php

namespace App\Models;

use App\Enum\ExchangeRateType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'currency_id',
        'type',
        'bid',
        'ask',
        'date',
    ];

    protected $casts = [
        'type' => ExchangeRateType::class
    ];


    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
