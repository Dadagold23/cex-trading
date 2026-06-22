<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\User;
use App\Models\UserCryptoWallet;
use App\Services\WalletProviders\InternalWalletAddressProvider;
use App\Services\WalletProviders\WalletAddressProviderInterface;

class CryptoWalletAddressService
{
    public function __construct(
        protected ?WalletAddressProviderInterface $provider = null
    ) {
        $this->provider ??= new InternalWalletAddressProvider();
    }

    public function createForUser(User $user): void
    {
        $cryptoCurrencies = Currency::where('type', 'crypto')
            ->where('is_active', true)
            ->get();

        foreach ($cryptoCurrencies as $currency) {
            $generated = $this->provider->generate($user, $currency);

            UserCryptoWallet::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'currency_id' => $currency->id,
                ],
                [
                    'address' => $generated['address'],
                    'network' => $generated['network'],
                    'tag' => $generated['tag'],
                    'provider' => $generated['provider'],
                    'is_active' => true,
                ]
            );
        }
    }

    public function regenerateForUser(User $user, Currency $currency): UserCryptoWallet
    {
        $generated = $this->provider->generate($user, $currency);

        $wallet = UserCryptoWallet::firstOrCreate(
            [
                'user_id' => $user->id,
                'currency_id' => $currency->id,
            ],
            [
                'address' => $generated['address'],
                'network' => $generated['network'],
                'tag' => $generated['tag'],
                'provider' => $generated['provider'],
                'is_active' => true,
            ]
        );

        $wallet->update([
            'address' => $generated['address'],
            'network' => $generated['network'],
            'tag' => $generated['tag'],
            'provider' => $generated['provider'],
            'last_regenerated_at' => now(),
            'is_active' => true,
        ]);

        return $wallet->fresh();
    }
}
