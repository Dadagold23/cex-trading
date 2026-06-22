<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;

class WalletService
{
    public function getOrCreateWallet(User $user, Currency $currency): Wallet
    {
        return Wallet::firstOrCreate(
            [
                'user_id' => $user->id,
                'currency_id' => $currency->id,
            ],
            [
                'available_balance' => 0,
                'held_balance' => 0,
            ]
        );
    }

    public function hasSufficientAvailableBalance(Wallet $wallet, float $amount): bool
    {
        return (float) $wallet->available_balance >= $amount;
    }
}
