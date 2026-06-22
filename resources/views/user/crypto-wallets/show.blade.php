@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">{{ $wallet->currency->code }} Deposit Wallet</h1>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm text-gray-500">{{ $wallet->currency->name }}</p>
                <h2 class="text-2xl font-semibold">{{ $wallet->currency->code }}</h2>
                <p class="text-sm text-gray-400 mt-1">Network: {{ $wallet->network ?: '-' }}</p>
            </div>

            <span class="px-3 py-1 rounded-full text-xs {{ $wallet->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $wallet->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-2">Wallet Address</p>
            <div class="rounded-xl border bg-gray-50 p-4">
                <p class="font-mono text-sm break-all" id="wallet-address">{{ $wallet->address }}</p>
            </div>
        </div>

        <a href="{{ route('user.crypto-wallets.deposit', $wallet->currency) }}"
            class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
            Deposit Now
        </a>

        @if($wallet->tag)
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-2">Memo / Tag</p>
                <div class="rounded-xl border bg-gray-50 p-4">
                    <p class="font-mono text-sm break-all">{{ $wallet->tag }}</p>
                </div>
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3">
            <button
                type="button"
                onclick="copyWalletAddress()"
                class="px-4 py-2 rounded-lg border hover:bg-gray-50"
            >
                Copy Address
            </button>

            <span id="copy-status" class="text-sm text-green-600 hidden">Address copied successfully.</span>

            <form method="POST" action="{{ route('user.crypto-wallets.regenerate', $wallet->currency) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                    Regenerate Address
                </button>
            </form>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold mb-3">Deposit Instructions</h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li>Send only <strong>{{ $wallet->currency->code }}</strong> to this address.</li>
                <li>Use the correct network: <strong>{{ $wallet->network ?: 'Specified network only' }}</strong>.</li>
                <li>Sending another asset or using the wrong network may result in permanent loss of funds.</li>
                <li>Wait for blockchain confirmation before balance update.</li>
                @if($wallet->tag)
                    <li>Include the required memo/tag when sending funds.</li>
                @endif
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4">QR Code</h3>

        <div class="rounded-2xl border bg-gray-50 p-4 flex items-center justify-center">
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($wallet->address) }}"
                alt="Wallet QR Code"
                class="rounded-lg"
            >
        </div>

        <p class="text-xs text-gray-400 mt-4">
            Created: {{ $wallet->created_at->format('Y-m-d H:i') }}
            @if($wallet->last_regenerated_at)
                <br>Last regenerated: {{ $wallet->last_regenerated_at->format('Y-m-d H:i') }}
            @endif
        </p>
    </div>
</div>

<script>
function copyWalletAddress() {
    const text = document.getElementById('wallet-address')?.innerText || '';
    const status = document.getElementById('copy-status');

    navigator.clipboard.writeText(text).then(() => {
        status.classList.remove('hidden');
        setTimeout(() => status.classList.add('hidden'), 2000);
    });
}
</script>
@endsection
