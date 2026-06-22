<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletLedger;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LedgerService
{
    public function credit(
        Wallet $wallet,
        float $amount,
        string $reference,
        string $entryType,
        ?string $description = null,
        array $meta = [],
        ?User $actor = null
    ): WalletLedger {
        return DB::transaction(function () use ($wallet, $amount, $reference, $entryType, $description, $meta, $actor) {
            $wallet->refresh();

            $before = (float) $wallet->available_balance;
            $after = $before + $amount;

            $wallet->update([
                'available_balance' => $after,
            ]);

            return WalletLedger::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'currency_id' => $wallet->currency_id,
                'reference' => $reference,
                'entry_type' => $entryType,
                'direction' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $description,
                'meta' => $meta,
                'created_by' => $actor?->id,
            ]);
        });
    }

    public function debit(
        Wallet $wallet,
        float $amount,
        string $reference,
        string $entryType,
        ?string $description = null,
        array $meta = [],
        ?User $actor = null
    ): WalletLedger {
        return DB::transaction(function () use ($wallet, $amount, $reference, $entryType, $description, $meta, $actor) {
            $wallet->refresh();

            $before = (float) $wallet->available_balance;

            if ($before < $amount) {
                throw new RuntimeException('Insufficient wallet balance.');
            }

            $after = $before - $amount;

            $wallet->update([
                'available_balance' => $after,
            ]);

            return WalletLedger::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'currency_id' => $wallet->currency_id,
                'reference' => $reference,
                'entry_type' => $entryType,
                'direction' => 'debit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $description,
                'meta' => $meta,
                'created_by' => $actor?->id,
            ]);
        });
    }

    public function moveAvailableToHeld(
        Wallet $wallet,
        float $amount,
        string $reference,
        string $entryType,
        ?string $description = null,
        array $meta = [],
        ?User $actor = null
    ): void {
        DB::transaction(function () use ($wallet, $amount, $reference, $entryType, $description, $meta, $actor) {
            $wallet->refresh();

            $availableBefore = (float) $wallet->available_balance;
            $heldBefore = (float) $wallet->held_balance;

            if ($availableBefore < $amount) {
                throw new RuntimeException('Insufficient available balance.');
            }

            $wallet->update([
                'available_balance' => $availableBefore - $amount,
                'held_balance' => $heldBefore + $amount,
            ]);

            WalletLedger::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'currency_id' => $wallet->currency_id,
                'reference' => $reference,
                'entry_type' => $entryType,
                'direction' => 'debit',
                'amount' => $amount,
                'balance_before' => $availableBefore,
                'balance_after' => $availableBefore - $amount,
                'description' => $description,
                'meta' => array_merge($meta, [
                    'held_before' => $heldBefore,
                    'held_after' => $heldBefore + $amount,
                    'movement' => 'available_to_held',
                ]),
                'created_by' => $actor?->id,
            ]);
        });
    }

    public function releaseHeldToAvailable(
        Wallet $wallet,
        float $amount,
        string $reference,
        string $entryType,
        ?string $description = null,
        array $meta = [],
        ?User $actor = null
    ): void {
        DB::transaction(function () use ($wallet, $amount, $reference, $entryType, $description, $meta, $actor) {
            $wallet->refresh();

            $availableBefore = (float) $wallet->available_balance;
            $heldBefore = (float) $wallet->held_balance;

            if ($heldBefore < $amount) {
                throw new RuntimeException('Insufficient held balance.');
            }

            $wallet->update([
                'available_balance' => $availableBefore + $amount,
                'held_balance' => $heldBefore - $amount,
            ]);

            WalletLedger::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'currency_id' => $wallet->currency_id,
                'reference' => $reference,
                'entry_type' => $entryType,
                'direction' => 'credit',
                'amount' => $amount,
                'balance_before' => $availableBefore,
                'balance_after' => $availableBefore + $amount,
                'description' => $description,
                'meta' => array_merge($meta, [
                    'held_before' => $heldBefore,
                    'held_after' => $heldBefore - $amount,
                    'movement' => 'held_to_available',
                ]),
                'created_by' => $actor?->id,
            ]);
        });
    }

    public function consumeHeldBalance(
        Wallet $wallet,
        float $amount,
        string $reference,
        string $entryType,
        ?string $description = null,
        array $meta = [],
        ?User $actor = null
    ): void {
        DB::transaction(function () use ($wallet, $amount, $reference, $entryType, $description, $meta, $actor) {
            $wallet->refresh();

            $availableBefore = (float) $wallet->available_balance;
            $heldBefore = (float) $wallet->held_balance;

            if ($heldBefore < $amount) {
                throw new RuntimeException('Insufficient held balance.');
            }

            $wallet->update([
                'held_balance' => $heldBefore - $amount,
            ]);

            WalletLedger::create([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'currency_id' => $wallet->currency_id,
                'reference' => $reference,
                'entry_type' => $entryType,
                'direction' => 'debit',
                'amount' => $amount,
                'balance_before' => $availableBefore,
                'balance_after' => $availableBefore,
                'description' => $description,
                'meta' => array_merge($meta, [
                    'held_before' => $heldBefore,
                    'held_after' => $heldBefore - $amount,
                    'movement' => 'held_consumed',
                ]),
                'created_by' => $actor?->id,
            ]);
        });
    }
}
