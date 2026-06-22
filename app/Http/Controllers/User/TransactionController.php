<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\TradeOrder;
use App\Models\Withdrawal;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $trades = TradeOrder::with(['currency', 'baseCurrency'])
            ->where('user_id', $userId)
            ->get()
            ->map(function ($trade) {
                return [
                    'date' => $trade->created_at,
                    'reference' => $trade->reference,
                    'category' => 'trade',
                    'type' => strtoupper($trade->order_type),
                    'currency' => $trade->currency->code,
                    'amount' => $trade->amount,
                    'status' => $trade->status,
                ];
            });

        $deposits = Deposit::with('currency')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($deposit) {
                return [
                    'date' => $deposit->created_at,
                    'reference' => $deposit->reference,
                    'category' => 'deposit',
                    'type' => 'DEPOSIT',
                    'currency' => $deposit->currency->code,
                    'amount' => $deposit->amount,
                    'status' => $deposit->status,
                ];
            });

        $withdrawals = Withdrawal::with('currency')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'date' => $withdrawal->created_at,
                    'reference' => $withdrawal->reference,
                    'category' => 'withdrawal',
                    'type' => 'WITHDRAWAL',
                    'currency' => $withdrawal->currency->code,
                    'amount' => $withdrawal->amount,
                    'status' => $withdrawal->status,
                ];
            });

        /** @var Collection $transactions */
        $transactions = $trades
            ->concat($deposits)
            ->concat($withdrawals)
            ->sortByDesc('date')
            ->values();

        return view('user.transactions.index', compact('transactions'));
    }
}
