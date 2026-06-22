<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\LiquidityAccount;
use App\Models\LiquidityMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LiquidityService
{
    public function getOrCreate(Currency $currency): LiquidityAccount
    {
        return LiquidityAccount::firstOrCreate(
            ['currency_id' => $currency->id],
            [
                'total_balance' => 0,
                'reserved_balance' => 0,
                'available_balance' => 0,
            ]
        );
    }

    public function add(
        Currency $currency,
        float $amount,
        string $reference,
        string $movementType,
        ?string $note = null,
        ?User $actor = null
    ): LiquidityMovement {
        return DB::transaction(function () use ($currency, $amount, $reference, $movementType, $note, $actor) {
            $account = $this->getOrCreate($currency);
            $account->refresh();

            $before = (float) $account->available_balance;
            $after = $before + $amount;

            $account->update([
                'total_balance' => (float) $account->total_balance + $amount,
                'available_balance' => $after,
            ]);

            return LiquidityMovement::create([
                'liquidity_account_id' => $account->id,
                'currency_id' => $currency->id,
                'reference' => $reference,
                'movement_type' => $movementType,
                'direction' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'note' => $note,
                'created_by' => $actor?->id,
            ]);
        });
    }

    public function deduct(
        Currency $currency,
        float $amount,
        string $reference,
        string $movementType,
        ?string $note = null,
        ?User $actor = null
    ): LiquidityMovement {
        return DB::transaction(function () use ($currency, $amount, $reference, $movementType, $note, $actor) {
            $account = $this->getOrCreate($currency);
            $account->refresh();

            $before = (float) $account->available_balance;

            if ($before < $amount) {
                throw new RuntimeException("Insufficient {$currency->code} liquidity.");
            }

            $after = $before - $amount;

            $account->update([
                'total_balance' => max((float) $account->total_balance - $amount, 0),
                'available_balance' => $after,
            ]);

            return LiquidityMovement::create([
                'liquidity_account_id' => $account->id,
                'currency_id' => $currency->id,
                'reference' => $reference,
                'movement_type' => $movementType,
                'direction' => 'debit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'note' => $note,
                'created_by' => $actor?->id,
            ]);
        });
    }
}
