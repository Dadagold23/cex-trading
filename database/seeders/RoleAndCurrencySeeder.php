<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\LiquidityAccount;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndCurrencySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'admin', 'user'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $currencies = [
            ['code' => 'NGN', 'name' => 'Nigerian Naira', 'type' => 'fiat', 'symbol' => '₦', 'network' => null, 'precision' => 2, 'is_active' => true, 'sort_order' => 1],
            ['code' => 'USD', 'name' => 'US Dollar', 'type' => 'fiat', 'symbol' => '$', 'network' => null, 'precision' => 2, 'is_active' => true, 'sort_order' => 2],
            ['code' => 'USDT', 'name' => 'Tether', 'type' => 'crypto', 'symbol' => '₮', 'network' => 'TRC20', 'precision' => 8, 'is_active' => true, 'sort_order' => 3],
            ['code' => 'BTC', 'name' => 'Bitcoin', 'type' => 'crypto', 'symbol' => '₿', 'network' => 'Bitcoin', 'precision' => 8, 'is_active' => true, 'sort_order' => 4],
            ['code' => 'ETH', 'name' => 'Ethereum', 'type' => 'crypto', 'symbol' => 'Ξ', 'network' => 'ERC20', 'precision' => 8, 'is_active' => true, 'sort_order' => 5],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@cointrading.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => '08000000000',
                'password' => Hash::make('Password123!'),
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['super_admin']);

        $user = User::firstOrCreate(
            ['email' => 'user@cointrading.com'],
            [
                'name' => 'Demo User',
                'username' => 'demouser',
                'phone' => '08000000002',
                'password' => Hash::make('Password123!'),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $user->syncRoles(['user']);

        foreach (['USDT', 'BTC', 'ETH', 'USD', 'NGN'] as $code) {
            $currency = Currency::where('code', $code)->first();

            if ($currency) {
                LiquidityAccount::updateOrCreate(
                    ['currency_id' => $currency->id],
                    [
                        'total_balance' => match ($code) {
                            'USDT' => 50000,
                            'BTC'  => 5,
                            'ETH'  => 100,
                            'USD'  => 100000,
                            'NGN'  => 50000000,
                            default => 0,
                        },
                        'reserved_balance' => 0,
                        'available_balance' => match ($code) {
                            'USDT' => 50000,
                            'BTC'  => 5,
                            'ETH'  => 100,
                            'USD'  => 100000,
                            'NGN'  => 50000000,
                            default => 0,
                        },
                    ]
                );
            }
        }

        $settings = [
            ['key' => 'site_name', 'value' => 'CEX - Trading  Platforrm  Platform', 'type' => 'string'],
            ['key' => 'support_email', 'value' => 'support@cointrading.com', 'type' => 'string'],
            ['key' => 'trade_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'deposit_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'withdrawal_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
