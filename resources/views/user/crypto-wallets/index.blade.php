@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Crypto Deposit Wallets</h1>

<div class="grid md:grid-cols-2 gap-6">
    @forelse($cryptoWallets as $wallet)
        <div class="bg-white rounded-2xl shadow p-6 border">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">{{ $wallet->currency->name }}</p>
                    <h2 class="text-xl font-semibold">{{ $wallet->currency->code }}</h2>
                    <p class="text-sm text-gray-400 mt-1">Network: {{ $wallet->network ?: '-' }}</p>
                </div>

                <span class="px-3 py-1 rounded-full text-xs {{ $wallet->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="mt-5">
                <p class="text-sm text-gray-500 mb-2">Wallet Address</p>
                <div class="rounded-xl border bg-gray-50 p-4">
                    <p class="font-mono text-sm break-all">{{ $wallet->address }}</p>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('user.crypto-wallets.show', $wallet->currency) }}"
                   class="px-4 py-2 rounded-lg border hover:bg-gray-50">
                    View Details
                </a>
            
                <a href="{{ route('user.crypto-wallets.deposit', $wallet->currency) }}"
                    class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                    Deposit
                </a>


                <form method="POST" action="{{ route('user.crypto-wallets.regenerate', $wallet->currency) }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                        Regenerate Address
                    </button>
                </form>
            </div>

            <p class="text-xs text-gray-400 mt-4">
                Created: {{ $wallet->created_at->format('Y-m-d H:i') }}
                @if($wallet->last_regenerated_at)
                    | Last regenerated: {{ $wallet->last_regenerated_at->format('Y-m-d H:i') }}
                @endif
            </p>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow p-6 text-gray-500">
            No crypto wallet addresses found.
        </div>
    @endforelse
</div>
@endsection
