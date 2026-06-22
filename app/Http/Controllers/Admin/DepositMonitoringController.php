<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DepositMonitoringController extends Controller
{
    public function index(): View
    {
        $duplicateTransactionHashes = Deposit::select('transaction_hash', DB::raw('COUNT(*) as total'))
            ->whereNotNull('transaction_hash')
            ->groupBy('transaction_hash')
            ->having('total', '>', 1)
            ->get();

        $duplicateToWalletAddresses = Deposit::select('to_wallet_address', DB::raw('COUNT(*) as total'))
            ->whereNotNull('to_wallet_address')
            ->groupBy('to_wallet_address')
            ->having('total', '>', 1)
            ->get();

        $duplicateFromWalletAddresses = Deposit::select('from_wallet_address', DB::raw('COUNT(*) as total'))
            ->whereNotNull('from_wallet_address')
            ->groupBy('from_wallet_address')
            ->having('total', '>', 1)
            ->get();

        $recentCryptoDeposits = Deposit::with(['user', 'currency'])
            ->where('method', 'crypto_transfer')
            ->latest()
            ->take(50)
            ->get();

        return view('admin.deposit-monitoring.index', compact(
            'duplicateTransactionHashes',
            'duplicateToWalletAddresses',
            'duplicateFromWalletAddresses',
            'recentCryptoDeposits'
        ));
    }
}
