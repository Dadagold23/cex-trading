<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trade_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_type', 20); // buy, sell
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('base_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->decimal('amount', 24, 8);
            $table->decimal('rate', 24, 8);
            $table->decimal('fee', 24, 8)->default(0);
            $table->decimal('subtotal', 24, 8)->default(0);
            $table->decimal('total', 24, 8)->default(0);
            $table->string('status', 30)->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('proof_file')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['order_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_orders');
    }
};
