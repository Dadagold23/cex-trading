<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\TradeOrder;
use App\Models\Withdrawal;
use App\Services\KycService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected KycService $kycService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user()->load(['wallets.currency', 'cryptoWallets.currency']);

        $recentTrades = TradeOrder::with(['currency', 'baseCurrency'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'wallet_count' => $user->wallets()->count(),
            'trades_count' => $user->tradeOrders()->count(),
            'deposits_count' => $user->deposits()->count(),
            'withdrawals_count' => $user->withdrawals()->count(),
            'pending_deposits' => $user->deposits()->where('status', 'pending')->count(),
            'pending_withdrawals' => $user->withdrawals()->where('status', 'pending')->count(),
            'completed_trades' => $user->tradeOrders()->where('status', 'completed')->count(),
            'unread_notifications' => $user->notifications()->where('is_read', false)->count(),
            'kyc_approved' => $user->kycRecords()->where('status', 'approved')->exists(),
        ];
            

        return view('user.dashboard', compact('user', 'recentTrades', 'stats'));
    }
}
