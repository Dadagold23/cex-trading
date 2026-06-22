<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Rate;
use App\Models\TradeOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class TradeService
{
    public function __construct(
        protected WalletService $walletService,
        protected LedgerService $ledgerService,
        protected LiquidityService $liquidityService,
        protected NotificationService $notificationService,
        protected AuditService $auditService
    ) {
    }

    public function createBuyOrder(
        User $user,
        Currency $currency,
        Currency $baseCurrency,
        float $amount
    ): TradeOrder {
        $rate = $this->getActiveRate($currency->id, $baseCurrency->id);
        $this->validateAmount($rate, $amount);

        $subtotal = $amount * (float) $rate->sell_rate;
        $fee = (float) $rate->sell_fee;
        $total = $subtotal + $fee;

        $baseWallet = $this->walletService->getOrCreateWallet($user, $baseCurrency);

        if (! $this->walletService->hasSufficientAvailableBalance($baseWallet, $total)) {
            throw new RuntimeException("Insufficient {$baseCurrency->code} balance.");
        }

        $trade = TradeOrder::create([
            'reference' => $this->generateReference('BUY'),
            'user_id' => $user->id,
            'order_type' => 'buy',
            'currency_id' => $currency->id,
            'base_currency_id' => $baseCurrency->id,
            'amount' => $amount,
            'rate' => $rate->sell_rate,
            'fee' => $fee,
            'subtotal' => $subtotal,
            'total' => $total,
            'status' => 'pending',
            'notes' => 'Buy trade submitted and awaiting admin approval.',
        ]);

        $this->notificationService->send(
            $user,
            'Buy Trade Submitted',
            "Your buy trade {$trade->reference} has been submitted for admin review.",
            'trade'
        );

        return $trade;
    }

    public function createSellOrder(
        User $user,
        Currency $currency,
        Currency $baseCurrency,
        float $amount
    ): TradeOrder {
        $rate = $this->getActiveRate($currency->id, $baseCurrency->id);
        $this->validateAmount($rate, $amount);

        $subtotal = $amount * (float) $rate->buy_rate;
        $fee = (float) $rate->buy_fee;
        $total = max($subtotal - $fee, 0);

        $coinWallet = $this->walletService->getOrCreateWallet($user, $currency);

        if (! $this->walletService->hasSufficientAvailableBalance($coinWallet, $amount)) {
            throw new RuntimeException("Insufficient {$currency->code} balance.");
        }

        $trade = TradeOrder::create([
            'reference' => $this->generateReference('SELL'),
            'user_id' => $user->id,
            'order_type' => 'sell',
            'currency_id' => $currency->id,
            'base_currency_id' => $baseCurrency->id,
            'amount' => $amount,
            'rate' => $rate->buy_rate,
            'fee' => $fee,
            'subtotal' => $subtotal,
            'total' => $total,
            'status' => 'pending',
            'notes' => 'Sell trade submitted and awaiting admin approval.',
        ]);

        $this->notificationService->send(
            $user,
            'Sell Trade Submitted',
            "Your sell trade {$trade->reference} has been submitted for admin review.",
            'trade'
        );

        return $trade;
    }

    public function approveTrade(TradeOrder $trade, User $admin, ?Request $request = null): TradeOrder
    {
        if ($trade->status !== 'pending') {
            throw new RuntimeException('Only pending trades can be approved.');
        }

        return DB::transaction(function () use ($trade, $admin, $request) {
            $trade->refresh();
            $trade->load(['user', 'currency', 'baseCurrency']);

            if ($trade->status !== 'pending') {
                throw new RuntimeException('This trade has already been processed.');
            }

            $old = ['status' => $trade->status];

            if ($trade->order_type === 'buy') {
                $this->processApprovedBuyTrade($trade, $admin);
            } else {
                $this->processApprovedSellTrade($trade, $admin);
            }

            $trade->update([
                'status' => 'completed',
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'completed_at' => now(),
            ]);

            $this->notificationService->send(
                $trade->user,
                'Trade Approved',
                "Your trade {$trade->reference} has been approved and completed.",
                'trade'
            );

            $this->auditService->log(
                $admin,
                'approve_trade',
                'trade',
                $trade->id,
                $old,
                ['status' => 'completed'],
                $request
            );

            return $trade->fresh(['user', 'currency', 'baseCurrency']);
        });
    }

    public function rejectTrade(TradeOrder $trade, User $admin, string $reason, ?Request $request = null): TradeOrder
    {
        if ($trade->status !== 'pending') {
            throw new RuntimeException('Only pending trades can be rejected.');
        }

        $old = ['status' => $trade->status];

        $trade->update([
            'status' => 'rejected',
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'rejected_reason' => $reason,
        ]);

        $this->notificationService->send(
            $trade->user,
            'Trade Rejected',
            "Your trade {$trade->reference} was rejected. Reason: {$reason}",
            'trade'
        );

        $this->auditService->log(
            $admin,
            'reject_trade',
            'trade',
            $trade->id,
            $old,
            ['status' => 'rejected', 'reason' => $reason],
            $request
        );

        return $trade->fresh(['user', 'currency', 'baseCurrency']);
    }

    protected function processApprovedBuyTrade(TradeOrder $trade, User $admin): void
    {
        $baseWallet = $this->walletService->getOrCreateWallet($trade->user, $trade->baseCurrency);
        $coinWallet = $this->walletService->getOrCreateWallet($trade->user, $trade->currency);

        $this->liquidityService->deduct(
            $trade->currency,
            (float) $trade->amount,
            $trade->reference,
            'trade_buy_liquidity_out',
            "Liquidity supplied to user for buy trade {$trade->reference}",
            $admin
        );

        $this->ledgerService->debit(
            $baseWallet,
            (float) $trade->total,
            $trade->reference,
            'trade_buy_debit',
            "Debited {$trade->baseCurrency->code} for buy trade",
            ['trade_id' => $trade->id, 'order_type' => 'buy'],
            $admin
        );

        $this->ledgerService->credit(
            $coinWallet,
            (float) $trade->amount,
            $trade->reference,
            'trade_buy_credit',
            "Credited {$trade->currency->code} from approved buy trade",
            ['trade_id' => $trade->id, 'order_type' => 'buy'],
            $admin
        );
    }

    protected function processApprovedSellTrade(TradeOrder $trade, User $admin): void
    {
        $coinWallet = $this->walletService->getOrCreateWallet($trade->user, $trade->currency);
        $baseWallet = $this->walletService->getOrCreateWallet($trade->user, $trade->baseCurrency);

        $this->ledgerService->debit(
            $coinWallet,
            (float) $trade->amount,
            $trade->reference,
            'trade_sell_debit',
            "Debited {$trade->currency->code} for approved sell trade",
            ['trade_id' => $trade->id, 'order_type' => 'sell'],
            $admin
        );

        $this->ledgerService->credit(
            $baseWallet,
            (float) $trade->total,
            $trade->reference,
            'trade_sell_credit',
            "Credited {$trade->baseCurrency->code} from sell trade",
            ['trade_id' => $trade->id, 'order_type' => 'sell'],
            $admin
        );

        $this->liquidityService->add(
            $trade->currency,
            (float) $trade->amount,
            $trade->reference,
            'trade_sell_liquidity_in',
            "Liquidity received from user sell trade {$trade->reference}",
            $admin
        );
    }

    protected function getActiveRate(int $currencyId, int $baseCurrencyId): Rate
    {
        $rate = Rate::where('currency_id', $currencyId)
            ->where('base_currency_id', $baseCurrencyId)
            ->where('is_active', true)
            ->first();

        if (! $rate) {
            throw new RuntimeException('Rate not available for this currency pair.');
        }

        return $rate;
    }

    protected function validateAmount(Rate $rate, float $amount): void
    {
        if ($amount <= 0) {
            throw new RuntimeException('Amount must be greater than zero.');
        }

        if ($amount < (float) $rate->min_amount) {
            throw new RuntimeException('Amount is below the minimum allowed.');
        }

        if (! is_null($rate->max_amount) && $amount > (float) $rate->max_amount) {
            throw new RuntimeException('Amount exceeds the maximum allowed.');
        }
    }

    protected function generateReference(string $prefix): string
    {
        return $prefix . '-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));
    }
}
