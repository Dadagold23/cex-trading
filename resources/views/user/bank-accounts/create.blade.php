@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Add Bank Account</h1>

<form method="POST" action="{{ route('user.bank-accounts.store') }}" class="bg-white rounded-2xl shadow p-6 max-w-3xl">
    @csrf

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 font-medium">Bank Name</label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Account Name</label>
            <input type="text" name="account_name" value="{{ old('account_name') }}" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Account Number</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">SWIFT Code</label>
            <input type="text" name="swift_code" value="{{ old('swift_code') }}" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Currency Code</label>
            <input type="text" name="currency_code" value="{{ old('currency_code', 'NGN') }}" class="w-full border rounded-lg px-4 py-3" required>
        </div>
    </div>

    <div class="mt-4">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1">
            <span>Set as default</span>
        </label>
    </div>

    <div class="mt-6">
        <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Save Bank Account</button>
    </div>
</form>
@endsection
