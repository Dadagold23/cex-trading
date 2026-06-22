<?php

namespace App\Policies;

use App\Models\Deposit;
use App\Models\User;

class DepositPolicy
{
    public function view(User $user, Deposit $deposit): bool
    {
        return $deposit->user_id === $user->id || $user->hasAnyRole(['admin', 'super_admin']);
    }
}
