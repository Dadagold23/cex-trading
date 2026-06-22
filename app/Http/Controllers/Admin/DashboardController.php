<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\KycRecord;
use App\Models\TradeOrder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::role('user')->count(),
            'wallets' => Wallet::count(),
            'pending_trades' => TradeOrder::where('status', 'pending')->count(),
            'completed_trades' => TradeOrder::where('status', 'completed')->count(),
            'pending_deposits' => Deposit::where('status', 'pending')->count(),
            'approved_deposits' => Deposit::where('status', 'approved')->count(),
            'rejected_deposits' => Deposit::where('status', 'rejected')->count(),
            'approved_trades' => TradeOrder::where('status', 'approved')->count(),
            'rejected_trades' => TradeOrder::where('status', 'rejected')->count(),
            'pending_crypto_deposits' => Deposit::where('status', 'pending')
                ->where('method', 'crypto_transfer')->count(),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'approved_withdrawals' => Withdrawal::where('status', 'approved')->count(),
            'rejected_withdrawals' => Withdrawal::where('status', 'rejected')->count(),
            'pending_kyc' => KycRecord::where('status', 'pending')->count(),
            'total_trade_volume' => TradeOrder::where('status', 'approved')->sum('total'),
        ];
    
        $recentTrades = TradeOrder::with(['user', 'currency', 'baseCurrency'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTrades'));
    }
}
