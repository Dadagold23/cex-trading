<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'base_currency_id',
        'buy_rate',
        'sell_rate',
        'buy_fee',
        'sell_fee',
        'min_amount',
        'max_amount',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }
}
