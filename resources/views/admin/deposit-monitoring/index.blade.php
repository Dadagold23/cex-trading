@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Deposit Monitoring</h1>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Duplicate TX Hashes</h2>
        <div class="space-y-3">
            @forelse($duplicateTransactionHashes as $item)
                <div class="border rounded-xl p-3">
                    <p class="font-mono text-xs break-all">{{ $item->transaction_hash }}</p>
                    <p class="text-sm text-red-600 mt-1">Occurrences: {{ $item->total }}</p>
                </div>
            @empty
                <p class="text-gray-500">No duplicate transaction hashes.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Duplicate Deposit Wallets</h2>
        <div class="space-y-3">
            @forelse($duplicateToWalletAddresses as $item)
                <div class="border rounded-xl p-3">
                    <p class="font-mono text-xs break-all">{{ $item->to_wallet_address }}</p>
                    <p class="text-sm text-orange-600 mt-1">Occurrences: {{ $item->total }}</p>
                </div>
            @empty
                <p class="text-gray-500">No duplicate destination wallet addresses.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Duplicate Sender Wallets</h2>
        <div class="space-y-3">
            @forelse($duplicateFromWalletAddresses as $item)
                <div class="border rounded-xl p-3">
                    <p class="font-mono text-xs break-all">{{ $item->from_wallet_address }}</p>
                    <p class="text-sm text-yellow-600 mt-1">Occurrences: {{ $item->total }}</p>
                </div>
            @empty
                <p class="text-gray-500">No duplicate sender wallet addresses.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <h2 class="text-xl font-semibold mb-4">Recent Crypto Deposits</h2>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Date</th>
                <th class="py-3">User</th>
                <th class="py-3">Currency</th>
                <th class="py-3">Amount</th>
                <th class="py-3">TX Hash</th>
                <th class="py-3">From</th>
                <th class="py-3">To</th>
                <th class="py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentCryptoDeposits as $deposit)
                <tr class="border-b align-top">
                    <td class="py-3">{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3">{{ $deposit->user->name }}</td>
                    <td class="py-3">{{ $deposit->currency->code }}</td>
                    <td class="py-3">{{ number_format((float) $deposit->amount, 8) }}</td>
                    <td class="py-3">
                        <div class="max-w-[170px] break-all font-mono text-xs">
                            {{ $deposit->transaction_hash ?: '-' }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="max-w-[170px] break-all font-mono text-xs">
                            {{ $deposit->from_wallet_address ?: '-' }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="max-w-[170px] break-all font-mono text-xs">
                            {{ $deposit->to_wallet_address ?: '-' }}
                        </div>
                    </td>
                    <td class="py-3">{{ ucfirst($deposit->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-4 text-gray-500">No crypto deposits found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
