<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->string('address')->unique();
            $table->string('network')->nullable();
            $table->string('tag')->nullable(); // memo/tag if needed
            $table->string('provider')->default('internal');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_regenerated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'currency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_crypto_wallets');
    }
};
