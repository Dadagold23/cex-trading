@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6" data-aos="fade-up">User Dashboard</h1>

<div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
        <p class="text-sm text-gray-500">Wallets</p>
        <p class="text-3xl font-bold">{{ $stats['wallet_count'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
        <p class="text-sm text-gray-500">Trades</p>
        <p class="text-3xl font-bold">{{ $stats['trades_count'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="300">
        <p class="text-sm text-gray-500">Deposits</p>
        <p class="text-3xl font-bold">{{ $stats['deposits_count'] }}</p>
    </div>
    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="400">
        <p class="text-sm text-gray-500">Withdrawals</p>
        <p class="text-3xl font-bold">{{ $stats['withdrawals_count'] }}</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 dashboard-card" data-aos="fade-right">
        <h2 class="text-xl font-semibold mb-4">Wallet Balances</h2>
        <div class="grid md:grid-cols-2 gap-4">
            @forelse($user->wallets as $wallet)
                <div class="border rounded-xl p-4 animated-card">
                    <h3 class="text-sm text-gray-500">{{ $wallet->currency->code }} Wallet</h3>
                    <p class="text-2xl font-bold mt-2">{{ number_format((float)$wallet->available_balance, 8) }}</p>
                    <p class="text-sm text-gray-500">Held: {{ number_format((float)$wallet->held_balance, 8) }}</p>
                </div>
            @empty
                <p class="text-gray-500">No wallets found.</p>
            @endforelse
        </div>
    </div>

    <div class="dashboard-card" data-aos="fade-left">
        <h2 class="text-xl font-semibold mb-4">Account Status</h2>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">KYC Status</p>
                <p class="font-semibold">{{ $stats['kyc_approved'] ? 'Approved' : 'Pending / Not Approved' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Unread Notifications</p>
                <p class="font-semibold">{{ $stats['unread_notifications'] }}</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('user.kyc.index') }}" class="dashboard-primary" data-click-sound="true">Manage KYC</a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow p-6 mt-8">
    <h2 class="text-xl font-semibold mb-4">Crypto Deposit Wallets</h2>

    <div class="grid md:grid-cols-2 gap-4">
        @forelse($user->cryptoWallets as $cryptoWallet)
            <div class="border rounded-xl p-4">
                <p class="text-sm text-gray-500">{{ $cryptoWallet->currency->name }} ({{ $cryptoWallet->currency->code }})</p>
                <p class="font-mono text-sm break-all mt-2">{{ $cryptoWallet->address }}</p>
                <p class="text-xs text-gray-400 mt-2">Network: {{ $cryptoWallet->network ?: '-' }}</p>
            </div>
        @empty
            <p class="text-gray-500">No crypto wallet addresses generated yet.</p>
        @endforelse
    </div>
</div>


<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Wallet Balances</h2>
        <div class="grid md:grid-cols-2 gap-4">
            @forelse($user->wallets as $wallet)
                <div class="border rounded-xl p-4">
                    <h3 class="text-sm text-gray-500">{{ $wallet->currency->code }} Wallet</h3>
                    <p class="text-2xl font-bold mt-2">{{ number_format((float)$wallet->available_balance, 8) }}</p>
                    <p class="text-sm text-gray-500">Held: {{ number_format((float)$wallet->held_balance, 8) }}</p>
                </div>
            @empty
                <p class="text-gray-500">No wallets found.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Crypto Deposit Wallets</h2>
            <a href="{{ route('user.crypto-wallets.index') }}" class="text-sm text-slate-700 hover:underline">Manage</a>
        </div>
        
        <div class="grid md:grid-cols-2 gap-4">
            @forelse($user->cryptoWallets as $cryptoWallet)
            <div class="border rounded-xl p-4">
                <p class="text-sm text-gray-500">{{ $cryptoWallet->currency->code }}</p>
                <p class="font-mono text-sm break-all mt-2">{{ $cryptoWallet->address }}</p>
            </div>
            @empty
            <p class="text-gray-500">No crypto wallet addresses generated yet.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 mt-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Crypto Deposit Wallets</h2>
        <a href="{{ route('user.crypto-wallets.index') }}" class="text-sm text-slate-700 hover:underline">Manage</a>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        @forelse($user->cryptoWallets as $cryptoWallet)
            <div class="border rounded-xl p-4">
                <p class="text-sm text-gray-500">{{ $cryptoWallet->currency->code }}</p>
                <p class="font-mono text-sm break-all mt-2">{{ $cryptoWallet->address }}</p>
                <a href="{{ route('user.crypto-wallets.show', $cryptoWallet->currency) }}"
                   class="inline-block mt-3 text-sm text-slate-700 hover:underline">
                    View Details
                </a>
            </div>
        @empty
            <p class="text-gray-500">No crypto wallet addresses generated yet.</p>
        @endforelse
    </div>
</div>


    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Account Status</h2>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">KYC Status</p>
                <p class="font-semibold">{{ $stats['kyc_approved'] ? 'Approved' : 'Pending / Not Approved' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Unread Notifications</p>
                <p class="font-semibold">{{ $stats['unread_notifications'] }}</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('user.kyc.index') }}" class="inline-block px-4 py-2 rounded-lg bg-slate-900 text-white">Manage KYC</a>
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
                        <td class="py-3 uppercase">{{ $trade->order_type }}</td>
                        <td class="py-3">{{ $trade->currency->code }}</td>
                        <td class="py-3">{{ number_format((float)$trade->amount, 8) }}</td>
                        <td class="py-3">{{ ucfirst($trade->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-gray-500">No trades yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
