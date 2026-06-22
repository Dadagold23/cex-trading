<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidityMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'liquidity_account_id',
        'currency_id',
        'reference',
        'movement_type',
        'direction',
        'amount',
        'balance_before',
        'balance_after',
        'note',
        'created_by',
    ];

    public function liquidityAccount()
    {
        return $this->belongsTo(LiquidityAccount::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
