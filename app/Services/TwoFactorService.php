<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorService
{
    public function __construct(
        protected Google2FA $google2fa
    ) {
    }

    public function generateSecret(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getQrCodeUrl(User $user, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            config('app.name', 'Coin Trading Platform'),
            $user->email,
            $secret
        );
    }

    public function enable(User $user, string $secret): User
    {
        $user->update([
            'two_factor_enabled' => true,
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $this->generateRecoveryCodes(),
        ]);

        return $user->fresh();
    }

    public function disable(User $user): User
    {
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
        ]);

        return $user->fresh();
    }

    public function verify(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            return false;
        }

        $secret = Crypt::decryptString($user->two_factor_secret);

        return $this->google2fa->verifyKey($secret, $code);
    }

    public function revealSecret(User $user): ?string
    {
        if (!$user->two_factor_secret) {
            return null;
        }

        return Crypt::decryptString($user->two_factor_secret);
    }

    public function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn () => Str::upper(Str::random(10)))
            ->values()
            ->all();
    }
}
