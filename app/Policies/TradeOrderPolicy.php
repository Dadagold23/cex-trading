<?php

namespace App\Policies;

use App\Models\TradeOrder;
use App\Models\User;

class TradeOrderPolicy
{
    public function view(User $user, TradeOrder $tradeOrder): bool
    {
        return $tradeOrder->user_id === $user->id || $user->hasAnyRole(['admin', 'super_admin']);
    }
}
