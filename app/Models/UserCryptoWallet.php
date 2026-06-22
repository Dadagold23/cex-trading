<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCryptoWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency_id',
        'address',
        'network',
        'tag',
        'provider',
        'is_active',
        'last_regenerated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_regenerated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
