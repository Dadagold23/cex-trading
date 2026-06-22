@extends('layouts.admin')
@php
    $stats = array_merge([
    'users' => 0,
    'wallets' => 0,
    'pending_trades' => 0,
    'completed_trades' => 0,
    'pending_deposits' => 0,
    'approved_deposits' => 0,
    'rejected_deposits' => 0,
    'pending_crypto_deposits' => 0,
    'pending_withdrawals' => 0,
    'approved_withdrawals' => 0,
    'rejected_withdrawals' => 0,
    'pending_kyc' => 0,
    'total_trade_volume' => 0,
    ], $stats ?? []);
@endphp

@section('content')
<div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Admin</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['users'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-700 font-bold">
                U
            </div>
        </div>
    </div>
</div>

<div class="grid md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
        <p class="text-sm text-gray-500">Approved Deposits</p>
        <p class="text-3xl font-bold">{{ $stats['approved_deposits'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
        <p class="text-sm text-gray-500">Rejected Deposits</p>
        <p class="text-3xl font-bold">{{ $stats['rejected_deposits'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="300">
        <p class="text-sm text-gray-500">Approved Withdrawals</p>
        <p class="text-3xl font-bold">{{ $stats['approved_withdrawals'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="400">
        <p class="text-sm text-gray-500">Rejected Withdrawals</p>
        <p class="text-3xl font-bold">{{ $stats['rejected_withdrawals'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="500">
        <p class="text-sm text-gray-500">Trade Volume</p>
        <p class="text-2xl font-bold">{{ number_format((float)$stats['total_trade_volume'], 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-sm text-gray-500">Pending KYC</p>
        <p class="text-3xl font-bold">{{ $stats['pending_kyc'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-sm text-gray-500">Pending Trades</p>
        <p class="text-3xl font-bold">{{ $stats['pending_trades'] }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-sm text-gray-500">Completed Trades</p>
        <p class="text-3xl font-bold">{{ $stats['completed_trades'] }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-sm text-gray-500">Pending Crypto Deposits</p>
        <p class="text-3xl font-bold">{{ $stats['pending_crypto_deposits'] }}</p>
    </div>
</div>



<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="content-card" data-aos="fade-up" data-aos-delay="100">
        <h3 class="font-semibold mb-4">Deposits Trend</h3>
        <div class="flex items-end gap-2 h-28">
            <div class="w-8 bg-emerald-200 rounded-t-xl h-12"></div>
            <div class="w-8 bg-emerald-300 rounded-t-xl h-20"></div>
            <div class="w-8 bg-emerald-400 rounded-t-xl h-16"></div>
            <div class="w-8 bg-emerald-500 rounded-t-xl h-24"></div>
            <div class="w-8 bg-emerald-600 rounded-t-xl h-14"></div>
            <div class="w-8 bg-emerald-700 rounded-t-xl h-28"></div>
        </div>
    </div>

    <div class="content-card" data-aos="fade-up" data-aos-delay="200">
        <h3 class="font-semibold mb-4">Trades Trend</h3>
        <div class="flex items-end gap-2 h-28">
            <div class="w-8 bg-slate-200 rounded-t-xl h-10"></div>
            <div class="w-8 bg-slate-300 rounded-t-xl h-18"></div>
            <div class="w-8 bg-slate-400 rounded-t-xl h-24"></div>
            <div class="w-8 bg-slate-500 rounded-t-xl h-14"></div>
            <div class="w-8 bg-slate-700 rounded-t-xl h-20"></div>
            <div class="w-8 bg-slate-900 rounded-t-xl h-26"></div>
        </div>
    </div>

    <div class="content-card" data-aos="fade-up" data-aos-delay="300">
        <h3 class="font-semibold mb-4">Withdrawals Trend</h3>
        <div class="flex items-end gap-2 h-28">
            <div class="w-8 bg-amber-200 rounded-t-xl h-14"></div>
            <div class="w-8 bg-amber-300 rounded-t-xl h-22"></div>
            <div class="w-8 bg-amber-400 rounded-t-xl h-12"></div>
            <div class="w-8 bg-amber-500 rounded-t-xl h-18"></div>
            <div class="w-8 bg-amber-600 rounded-t-xl h-24"></div>
            <div class="w-8 bg-amber-700 rounded-t-xl h-16"></div>
        </div>
    </div>
</div>


<div class="dashboard-card" data-aos="fade-up">
    <h2 class="text-xl font-semibold mb-4">Activity Overview</h2>

    <div class="space-y-4">
        <div>
            <div class="flex items-center justify-between text-sm mb-1">
                <span>Deposits</span>
                <span>{{ $stats['deposits_count'] ?? $stats['pending_deposits'] ?? 0 }}</span>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 65%;"></div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between text-sm mb-1">
                <span>Withdrawals</span>
                <span>{{ $stats['withdrawals_count'] ?? $stats['pending_withdrawals'] ?? 0 }}</span>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full" style="width: 45%;"></div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between text-sm mb-1">
                <span>Trades</span>
                <span>{{ $stats['trades_count'] ?? $stats['completed_trades'] ?? 0 }}</span>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-slate-900 rounded-full" style="width: 75%;"></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Recent Trades</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Reference</th>
                    <th class="py-3">User</th>
                    <th class="py-3">Type</th>
                    <th class="py-3">Currency</th>
                    <th class="py-3">Amount</th>
                    <th class="py-3">Status</th>
                </tr>
            </thead>
            
            <tbody>
                @forelse($recentTrades as $trade)
                    <tr class="border-b">
                        <td class="py-3">{{ $trade->reference }}</td>
                        <td class="py-3">{{ $trade->user->name }}</td>
                        <td class="py-3">{{ strtoupper($trade->order_type) }}</td>
                        <td class="py-3">{{ $trade->currency->code }}</td>
                        <td class="py-3">{{ number_format((float)$trade->amount, 8) }}</td>
                        <td class="py-3">{{ ucfirst($trade->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-gray-500">No trades yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>
@endsection
