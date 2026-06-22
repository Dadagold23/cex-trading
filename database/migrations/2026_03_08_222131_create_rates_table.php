<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('base_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->decimal('buy_rate', 24, 8);
            $table->decimal('sell_rate', 24, 8);
            $table->decimal('buy_fee', 24, 8)->default(0);
            $table->decimal('sell_fee', 24, 8)->default(0);
            $table->decimal('min_amount', 24, 8)->default(0);
            $table->decimal('max_amount', 24, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['currency_id', 'base_currency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
