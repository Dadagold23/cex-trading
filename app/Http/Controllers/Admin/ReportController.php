<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\TradeOrder;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $summary = [
            'users' => User::role('user')->count(),
            'deposits_total' => (float) Deposit::where('status', 'approved')->sum('amount'),
            'withdrawals_total' => (float) Withdrawal::where('status', 'approved')->sum('amount'),
            'trades_total' => TradeOrder::count(),
            'completed_trades' => TradeOrder::where('status', 'completed')->count(),
        ];

        return view('admin.reports.index', compact('summary'));
    }

    public function trades(): View
    {
        $trades = TradeOrder::with(['user', 'currency', 'baseCurrency'])
            ->latest()
            ->paginate(25);

        return view('admin.reports.trades', compact('trades'));
    }

    public function deposits(): View
    {
        $deposits = Deposit::with(['user', 'currency'])
            ->latest()
            ->paginate(25);

        return view('admin.reports.deposits', compact('deposits'));
    }

    public function withdrawals(): View
    {
        $withdrawals = Withdrawal::with(['user', 'currency'])
            ->latest()
            ->paginate(25);

        return view('admin.reports.withdrawals', compact('withdrawals'));
    }

    public function users(): View
    {
        $users = User::role('user')
            ->withCount(['deposits', 'withdrawals', 'tradeOrders'])
            ->latest()
            ->paginate(25);

        return view('admin.reports.users', compact('users'));
    }

    public function revenue(): View
    {
        $tradeFees = (float) TradeOrder::where('status', 'completed')->sum('fee');
        $withdrawalFees = (float) Withdrawal::where('status', 'approved')->sum('fee');

        $revenue = [
            'trade_fees' => $tradeFees,
            'withdrawal_fees' => $withdrawalFees,
            'total_revenue' => $tradeFees + $withdrawalFees,
        ];

        $monthlyTrades = TradeOrder::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(fee) as total_fee')
            )
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('admin.reports.revenue', compact('revenue', 'monthlyTrades'));
    }

    public function chartData(): JsonResponse
    {
        $trades = TradeOrder::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->limit(30)
            ->get();

        $deposits = Deposit::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->limit(30)
            ->get();

        $withdrawals = Withdrawal::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->limit(30)
            ->get();

        return response()->json([
            'trades' => $trades,
            'deposits' => $deposits,
            'withdrawals' => $withdrawals,
        ]);
    }
}
