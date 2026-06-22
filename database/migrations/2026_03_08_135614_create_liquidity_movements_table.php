<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('liquidity_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liquidity_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->index();
            $table->string('movement_type');
            $table->string('direction');
            $table->decimal('amount', 24, 8);
            $table->decimal('balance_before', 24, 8);
            $table->decimal('balance_after', 24, 8);
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidity_movements');
    }
};
