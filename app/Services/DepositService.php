<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class DepositService
{
    public function __construct(
        protected WalletService $walletService,
        protected LedgerService $ledgerService,
        protected NotificationService $notificationService
    ) {
    }

    public function createDepositRequest(User $user, array $data): Deposit
    {
        $currency = Currency::findOrFail($data['currency_id']);

        if (!empty($data['transaction_hash'])) {
            $exists = Deposit::where('transaction_hash', $data['transaction_hash'])->exists();

            if ($exists) {
                throw new RuntimeException('This transaction hash has already been submitted.');
            }
        }

        $proofPath = null;
        if (!empty($data['proof_file']) && $data['proof_file'] instanceof UploadedFile) {
            $proofPath = $data['proof_file']->store('deposits', 'public');
        }

        $deposit = Deposit::create([
            'reference' => $this->generateReference(),
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'amount' => $data['amount'],
            'method' => $data['method'],
            'sender_name' => $data['sender_name'] ?? null,
            'sender_account' => $data['sender_account'] ?? null,
            'proof_file' => $proofPath,
            'gateway_reference' => $data['gateway_reference'] ?? null,
            'transaction_hash' => $data['transaction_hash'] ?? null,
            'from_wallet_address' => $data['from_wallet_address'] ?? null,
            'to_wallet_address' => $data['to_wallet_address'] ?? null,
            'network' => $data['network'] ?? null,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        $this->logStatus(
            $deposit,
            'pending',
            'Deposit request submitted by user.',
            $user,
            'user'
        );

        $this->notificationService->send(
            $user,
            'Deposit Request Submitted',
            "Your deposit request {$deposit->reference} has been submitted and is awaiting review.",
            'deposit'
        );

        return $deposit;
    }

    public function approveDeposit(Deposit $deposit, User $admin, ?string $note = null): Deposit
    {
        if ($deposit->status !== 'pending') {
            throw new RuntimeException('Only pending deposits can be approved.');
        }

        return DB::transaction(function () use ($deposit, $admin, $note) {
            $deposit->refresh();
            $deposit->loadMissing(['user', 'currency']);

            if ($deposit->status !== 'pending') {
                throw new RuntimeException('This deposit has already been processed.');
            }

            $wallet = $this->walletService->getOrCreateWallet($deposit->user, $deposit->currency);

            $deposit->update([
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'confirmed_at' => now(),
                'notes' => $note ?: $deposit->notes,
            ]);

            $this->ledgerService->credit(
                $wallet,
                (float) $deposit->amount,
                $deposit->reference,
                'deposit_credit',
                "Deposit approved for {$deposit->currency->code}",
                [
                    'deposit_id' => $deposit->id,
                    'method' => $deposit->method,
                    'transaction_hash' => $deposit->transaction_hash,
                ],
                $admin
            );

            $this->logStatus(
                $deposit,
                'approved',
                $note ?: 'Deposit approved and wallet credited.',
                $admin,
                'admin'
            );

            $this->notificationService->send(
                $deposit->user,
                'Deposit Approved',
                "Your deposit {$deposit->reference} has been approved and your wallet has been credited.",
                'deposit'
            );

            return $deposit->fresh(['user', 'currency']);
        });
    }

    public function rejectDeposit(Deposit $deposit, User $admin, ?string $reason = null): Deposit
    {
        if ($deposit->status !== 'pending') {
            throw new RuntimeException('Only pending deposits can be rejected.');
        }

        $deposit->update([
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        $this->logStatus(
            $deposit,
            'rejected',
            $reason ?: 'Deposit rejected.',
            $admin,
            'admin'
        );

        $this->notificationService->send(
            $deposit->user,
            'Deposit Rejected',
            "Your deposit {$deposit->reference} was rejected. Reason: " . ($reason ?: 'Not provided'),
            'deposit'
        );

        return $deposit->fresh(['user', 'currency']);
    }

    public function confirmViaWebhook(Deposit $deposit, ?string $note = null): Deposit
    {
        if ($deposit->status !== 'pending') {
            return $deposit;
        }

        return DB::transaction(function () use ($deposit, $note) {
            $deposit->refresh();
            $deposit->loadMissing(['user', 'currency']);

            if ($deposit->status !== 'pending') {
                return $deposit;
            }

            $wallet = $this->walletService->getOrCreateWallet($deposit->user, $deposit->currency);

            $deposit->update([
                'status' => 'approved',
                'confirmed_at' => now(),
                'notes' => $note ?: $deposit->notes,
            ]);

            $this->ledgerService->credit(
                $wallet,
                (float) $deposit->amount,
                $deposit->reference,
                'deposit_credit_webhook',
                "Deposit auto-confirmed for {$deposit->currency->code}",
                [
                    'deposit_id' => $deposit->id,
                    'transaction_hash' => $deposit->transaction_hash,
                    'confirmation_source' => 'webhook',
                ],
                null
            );

            $this->logStatus(
                $deposit,
                'approved',
                $note ?: 'Deposit confirmed automatically via webhook.',
                null,
                'webhook'
            );

            $this->notificationService->send(
                $deposit->user,
                'Deposit Confirmed',
                "Your deposit {$deposit->reference} has been automatically confirmed.",
                'deposit'
            );

            return $deposit->fresh(['user', 'currency']);
        });
    }

    protected function logStatus(Deposit $deposit, string $status, ?string $note = null, ?User $actor = null, string $source = 'system'): void
    {
        $deposit->statusLogs()->create([
            'status' => $status,
            'note' => $note,
            'created_by' => $actor?->id,
            'source' => $source,
        ]);
    }

    protected function generateReference(): string
    {
        return 'DEP-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
    }
}
