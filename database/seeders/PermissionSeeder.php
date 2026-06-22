<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage rates',
            'manage trades',
            'manage deposits',
            'manage withdrawals',
            'manage kyc',
            'manage liquidity',
            'view audit logs',
            'manage support',
            'manage settings',
            'manage admin users',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $superAdmin->syncPermissions($permissions);

        $admin->syncPermissions([
            'manage rates',
            'manage trades',
            'manage deposits',
            'manage withdrawals',
            'manage kyc',
            'manage liquidity',
            'view audit logs',
            'manage support',
            'view reports',
        ]);

        $user->syncPermissions([]);
    }
}
