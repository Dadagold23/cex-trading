<?php

namespace App\Services\WalletProviders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Str;

class InternalWalletAddressProvider implements WalletAddressProviderInterface
{
    public function generate(User $user, Currency $currency): array
    {
        $address = match (strtoupper($currency->code)) {
            'BTC'  => 'btc_' . Str::lower(Str::random(34)),
            'ETH'  => '0x' . Str::lower(Str::random(40)),
            'USDT' => 'usdt_' . Str::lower(Str::random(30)),
            default => Str::lower($currency->code) . '_' . Str::lower(Str::random(32)),
        };

        return [
            'address' => $address,
            'network' => $currency->network,
            'tag' => null,
            'provider' => 'internal',
        ];
    }
}
