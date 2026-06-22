<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->unique()->after('email');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role');
            }

            if (!Schema::hasColumn('users', 'withdrawal_pin')) {
                $table->string('withdrawal_pin')->nullable()->after('status');
            }

            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('withdrawal_pin');
            }

            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            }

            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('phone_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drop = [];

            foreach ([
                'username',
                'phone',
                'role',
                'status',
                'withdrawal_pin',
                'two_factor_enabled',
                'phone_verified_at',
                'last_login_at',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
