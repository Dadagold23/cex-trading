@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">{{ $wallet->currency->code }} Deposit Instructions</h1>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Step 1: Send Funds</h2>

        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-2">Deposit Address</p>
            <div class="rounded-xl border bg-gray-50 p-4">
                <p class="font-mono text-sm break-all">{{ $wallet->address }}</p>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-sm text-gray-500 mb-2">Network</p>
            <div class="rounded-xl border bg-gray-50 p-4">
                <p class="font-semibold">{{ $wallet->network ?: '-' }}</p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3">Important Instructions</h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li>Send only <strong>{{ $wallet->currency->code }}</strong> to this address.</li>
                <li>Use the correct network: <strong>{{ $wallet->network ?: 'Specified network only' }}</strong>.</li>
                <li>Using the wrong network may permanently lose your funds.</li>
                <li>After sending, submit the transaction hash below for admin confirmation.</li>
            </ul>
        </div>

        <h2 class="text-xl font-semibold mb-4">Step 2: Submit Deposit Proof</h2>

        <form method="POST" action="{{ route('user.deposits.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <input type="hidden" name="currency_id" value="{{ $wallet->currency->id }}">
            <input type="hidden" name="method" value="crypto_transfer">
            <input type="hidden" name="to_wallet_address" value="{{ $wallet->address }}">
            <input type="hidden" name="network" value="{{ $wallet->network }}">

            <div>
                <label class="block mb-2 font-medium">Amount Sent</label>
                <input type="number" step="0.00000001" name="amount" value="{{ old('amount') }}"
                    class="w-full border rounded-lg px-4 py-3" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">Transaction Hash</label>
                <input type="text" name="transaction_hash" value="{{ old('transaction_hash') }}"
                    class="w-full border rounded-lg px-4 py-3" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">Sender Wallet Address</label>
                <input type="text" name="from_wallet_address" value="{{ old('from_wallet_address') }}"
                    class="w-full border rounded-lg px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">Screenshot / Proof</label>
                <input type="file" name="proof_file" class="w-full border rounded-lg px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">Notes</label>
                <textarea name="notes" rows="4" class="w-full border rounded-lg px-4 py-3">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                Submit Deposit Request
            </button>
        </form>
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
    </div>
</div>
@endsection
