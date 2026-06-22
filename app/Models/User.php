<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected string $guard_name = 'web';

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'withdrawal_pin',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'two_factor_recovery_codes',

    ];

    protected $hidden = [
        'password',
        'remember_token',
        'withdrawal_pin',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
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

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function kycRecords()
    {
        return $this->hasMany(KycRecord::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }
    public function cryptoWallets()
    {
        return $this->hasMany(UserCryptoWallet::class);
    }
    public function hasTwoFactorEnabled(): bool
    {
        return (bool) $this->two_factor_enabled
            && !empty($this->two_factor_secret)
            && !empty($this->two_factor_confirmed_at);
    }

    public function isAdminLike(): bool
    {
        return $this->hasAnyRole(['admin', 'super_admin']);
    }


}
