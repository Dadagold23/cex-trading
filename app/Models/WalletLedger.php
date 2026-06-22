<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'user_id',
        'currency_id',
        'reference',
        'entry_type',
        'direction',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'meta',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
