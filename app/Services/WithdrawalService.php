<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Currency;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class WithdrawalService
{
    public function __construct(
        protected WalletService $walletService,
        protected LedgerService $ledgerService,
        protected KycService $kycService,
        protected NotificationService $notificationService,
        protected SecurityService $securityService
    ) {
    }

    public function createWithdrawalRequest(User $user, array $data): Withdrawal
    {
        if (! $this->kycService->isApproved($user)) {
            throw new RuntimeException('KYC must be approved before you can withdraw.');
        }

        if (! $this->securityService->verifyWithdrawalPin($user, (string) $data['withdrawal_pin'])) {
            throw new RuntimeException('Invalid withdrawal PIN.');
        }

        $currency = Currency::findOrFail($data['currency_id']);
        $wallet = $this->walletService->getOrCreateWallet($user, $currency);

        $amount = (float) $data['amount'];
        $fee = 0.0;
        $netAmount = max($amount - $fee, 0);

        if (! $this->walletService->hasSufficientAvailableBalance($wallet, $amount)) {
            throw new RuntimeException("Insufficient {$currency->code} balance.");
        }

        $destinationDetails = $this->buildDestinationDetails($user, $data);

        return DB::transaction(function () use ($user, $currency, $wallet, $amount, $fee, $netAmount, $data, $destinationDetails) {
            $reference = $this->generateReference();

            $withdrawal = Withdrawal::create([
                'reference' => $reference,
                'user_id' => $user->id,
                'currency_id' => $currency->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $netAmount,
                'method' => $data['method'],
                'destination_type' => $data['destination_type'],
                'destination_details' => $destinationDetails,
                'status' => 'pending',
            ]);

            $this->ledgerService->moveAvailableToHeld(
                $wallet,
                $amount,
                $reference,
                'withdrawal_hold',
                "Funds held for withdrawal {$reference}",
                [
                    'withdrawal_id' => $withdrawal->id,
                    'destination_type' => $data['destination_type'],
                ],
                $user
            );

            $this->notificationService->send(
                $user,
                'Withdrawal Request Submitted',
                "Your withdrawal request {$withdrawal->reference} has been submitted and funds were placed on hold.",
                'withdrawal'
            );

            return $withdrawal->fresh(['currency']);
        });
    }

    public function approveWithdrawal(Withdrawal $withdrawal, User $admin): Withdrawal
    {
        if ($withdrawal->status !== 'pending') {
            throw new RuntimeException('Only pending withdrawals can be approved.');
        }

        return DB::transaction(function () use ($withdrawal, $admin) {
            $withdrawal->refresh();
            $withdrawal->loadMissing(['user', 'currency']);

            if ($withdrawal->status !== 'pending') {
                throw new RuntimeException('This withdrawal has already been processed.');
            }

            $wallet = $this->walletService->getOrCreateWallet($withdrawal->user, $withdrawal->currency);

            $withdrawal->update([
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'processed_at' => now(),
            ]);

            $this->ledgerService->consumeHeldBalance(
                $wallet,
                (float) $withdrawal->amount,
                $withdrawal->reference,
                'withdrawal_debit',
                "Withdrawal approved and settled for {$withdrawal->currency->code}",
                [
                    'withdrawal_id' => $withdrawal->id,
                ],
                $admin
            );

            $this->notificationService->send(
                $withdrawal->user,
                'Withdrawal Approved',
                "Your withdrawal {$withdrawal->reference} has been approved and processed.",
                'withdrawal'
            );

            return $withdrawal->fresh(['user', 'currency']);
        });
    }

    public function rejectWithdrawal(Withdrawal $withdrawal, User $admin, string $reason): Withdrawal
    {
        if ($withdrawal->status !== 'pending') {
            throw new RuntimeException('Only pending withdrawals can be rejected.');
        }

        return DB::transaction(function () use ($withdrawal, $admin, $reason) {
            $withdrawal->refresh();
            $withdrawal->loadMissing(['user', 'currency']);

            if ($withdrawal->status !== 'pending') {
                throw new RuntimeException('This withdrawal has already been processed.');
            }

            $wallet = $this->walletService->getOrCreateWallet($withdrawal->user, $withdrawal->currency);

            $withdrawal->update([
                'status' => 'rejected',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'rejection_reason' => $reason,
            ]);

            $this->ledgerService->releaseHeldToAvailable(
                $wallet,
                (float) $withdrawal->amount,
                $withdrawal->reference,
                'withdrawal_reversal',
                'Withdrawal rejected and funds returned to available balance',
                [
                    'withdrawal_id' => $withdrawal->id,
                ],
                $admin
            );

            $this->notificationService->send(
                $withdrawal->user,
                'Withdrawal Rejected',
                "Your withdrawal {$withdrawal->reference} was rejected. Funds have been returned to your wallet.",
                'withdrawal'
            );

            return $withdrawal->fresh(['user', 'currency']);
        });
    }

    protected function buildDestinationDetails(User $user, array $data): array
    {
        if (($data['destination_type'] ?? '') === 'bank_account') {
            $bankAccount = BankAccount::where('id', $data['bank_account_id'] ?? 0)
                ->where('user_id', $user->id)
                ->first();

            if (! $bankAccount) {
                throw new RuntimeException('Valid bank account not found.');
            }

            return [
                'bank_account_id' => $bankAccount->id,
                'bank_name' => $bankAccount->bank_name,
                'account_name' => $bankAccount->account_name,
                'account_number' => $bankAccount->account_number,
                'currency_code' => $bankAccount->currency_code,
            ];
        }

        if (($data['destination_type'] ?? '') === 'crypto_wallet') {
            if (empty($data['wallet_address'])) {
                throw new RuntimeException('Wallet address is required.');
            }

            return [
                'wallet_address' => $data['wallet_address'],
                'wallet_network' => $data['wallet_network'] ?? null,
            ];
        }

        throw new RuntimeException('Invalid destination type.');
    }

    protected function generateReference(): string
    {
        return 'WDR-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
    }
}
