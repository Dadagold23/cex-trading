<?php

namespace App\Services\WalletProviders;

use App\Models\Currency;
use App\Models\User;

interface WalletAddressProviderInterface
{
    public function generate(User $user, Currency $currency): array;
}
