<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin2@cointrading.com'],
            [
                'name' => 'Platform Admin',
                'username' => 'platformadmin',
                'phone' => '08000000001',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);
    }
}
