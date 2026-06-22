<?php

namespace App\Services;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class SecurityService
{
    public function updatePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw new RuntimeException('Current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function updateWithdrawalPin(User $user, string $pin): void
    {
        $user->update([
            'withdrawal_pin' => Hash::make($pin),
        ]);
    }

    public function verifyWithdrawalPin(User $user, string $pin): bool
    {
        if (! $user->withdrawal_pin) {
            return false;
        }

        return Hash::check($pin, $user->withdrawal_pin);
    }

    public function logLogin(?User $user, Request $request, string $status = 'success'): LoginLog
    {
        return LoginLog::create([
            'user_id' => $user?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => null,
            'platform' => null,
            'browser' => null,
            'login_status' => $status,
        ]);
    }
}
