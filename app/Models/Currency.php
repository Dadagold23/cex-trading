<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'symbol',
        'network',
        'precision',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function tradeOrders()
    {
        return $this->hasMany(TradeOrder::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function liquidityAccount()
    {
        return $this->hasOne(LiquidityAccount::class);
    }

    public function userCryptoWallets()
{
    return $this->hasMany(UserCryptoWallet::class);
}

}
