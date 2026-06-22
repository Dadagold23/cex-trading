<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidityAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'total_balance',
        'reserved_balance',
        'available_balance',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function movements()
    {
        return $this->hasMany(LiquidityMovement::class);
    }
}
