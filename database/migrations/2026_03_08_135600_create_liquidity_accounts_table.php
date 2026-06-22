<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('liquidity_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_balance', 24, 8)->default(0);
            $table->decimal('reserved_balance', 24, 8)->default(0);
            $table->decimal('available_balance', 24, 8)->default(0);
            $table->timestamps();

            $table->unique('currency_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidity_accounts');
    }
};
