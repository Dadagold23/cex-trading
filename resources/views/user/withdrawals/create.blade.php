@extends('layouts.user')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6" data-aos="fade-up">
        <h1 class="text-3xl font-bold">Create Withdrawal Request</h1>
        <p class="text-gray-500 mt-1">Withdraw to a bank account or crypto wallet securely.</p>
    </div>

    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100" x-data="{ destinationType: '{{ old('destination_type') }}' || '' }">
        <form method="POST" action="{{ route('user.withdrawals.store') }}" class="grid md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label class="block mb-2 font-medium">Currency</label>
                <select name="currency_id" class="w-full border rounded-xl px-4 py-3" required>
                    <option value="">Select currency</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" @selected(old('currency_id') == $currency->id)>
                            {{ $currency->name }} ({{ $currency->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium">Amount</label>
                <input type="number" step="0.00000001" name="amount" value="{{ old('amount') }}" class="w-full border rounded-xl px-4 py-3" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">Method</label>
                <select name="method" class="w-full border rounded-xl px-4 py-3" required>
                    <option value="">Select method</option>
                    @foreach($methods as $key => $label)
                        <option value="{{ $key }}" @selected(old('method') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium">Destination Type</label>
                <select name="destination_type" x-model="destinationType" class="w-full border rounded-xl px-4 py-3" required>
                    <option value="">Select destination</option>
                    <option value="bank_account">Bank Account</option>
                    <option value="crypto_wallet">Crypto Wallet</option>
                </select>
            </div>

            <div x-show="destinationType === 'bank_account'" x-cloak>
                <label class="block mb-2 font-medium">Bank Account</label>
                <select name="bank_account_id" class="w-full border rounded-xl px-4 py-3">
                    <option value="">Select bank account</option>
                    @foreach($bankAccounts as $bankAccount)
                        <option value="{{ $bankAccount->id }}" @selected(old('bank_account_id') == $bankAccount->id)>
                            {{ $bankAccount->bank_name }} - {{ $bankAccount->account_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div x-show="destinationType === 'crypto_wallet'" x-cloak>
                <label class="block mb-2 font-medium">Wallet Address</label>
                <input type="text" name="wallet_address" value="{{ old('wallet_address') }}" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div x-show="destinationType === 'crypto_wallet'" x-cloak>
                <label class="block mb-2 font-medium">Wallet Network</label>
                <input type="text" name="wallet_network" value="{{ old('wallet_network') }}" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">Withdrawal PIN</label>
                <input type="password" name="withdrawal_pin" maxlength="4" class="w-full border rounded-xl px-4 py-3" required>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-medium">Notes</label>
                <textarea name="notes" rows="4" class="w-full border rounded-xl px-4 py-3">{{ old('notes') }}</textarea>
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <button type="submit" class="dashboard-primary">Submit Withdrawal</button>
                <a href="{{ route('user.withdrawals.index') }}" class="dashboard-action" data-click-sound="true">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
