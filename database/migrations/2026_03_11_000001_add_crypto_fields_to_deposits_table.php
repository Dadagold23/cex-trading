<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            if (!Schema::hasColumn('deposits', 'transaction_hash')) {
                $table->string('transaction_hash')->nullable()->after('gateway_reference');
            }

            if (!Schema::hasColumn('deposits', 'from_wallet_address')) {
                $table->string('from_wallet_address')->nullable()->after('transaction_hash');
            }

            if (!Schema::hasColumn('deposits', 'to_wallet_address')) {
                $table->string('to_wallet_address')->nullable()->after('from_wallet_address');
            }

            if (!Schema::hasColumn('deposits', 'network')) {
                $table->string('network')->nullable()->after('to_wallet_address');
            }

            if (!Schema::hasColumn('deposits', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $drop = [];

            foreach ([
                'transaction_hash',
                'from_wallet_address',
                'to_wallet_address',
                'network',
                'confirmed_at',
            ] as $column) {
                if (Schema::hasColumn('deposits', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
