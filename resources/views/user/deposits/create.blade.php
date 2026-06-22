@extends('layouts.user')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6" data-aos="fade-up">
        <h1 class="text-3xl font-bold">Create Deposit Request</h1>
        <p class="text-gray-500 mt-1">Submit a fiat or gateway deposit request for admin confirmation.</p>
    </div>

    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
        <form method="POST" action="{{ route('user.deposits.store') }}" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-6">
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
                <label class="block mb-2 font-medium">Sender Name</label>
                <input type="text" name="sender_name" value="{{ old('sender_name') }}" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">Sender Account / Wallet</label>
                <input type="text" name="sender_account" value="{{ old('sender_account') }}" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">Gateway Reference</label>
                <input type="text" name="gateway_reference" value="{{ old('gateway_reference') }}" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-medium">Proof File</label>
                <input type="file" name="proof_file" class="w-full border rounded-xl px-4 py-3">
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-medium">Notes</label>
                <textarea name="notes" rows="4" class="w-full border rounded-xl px-4 py-3">{{ old('notes') }}</textarea>
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <button type="submit" class="dashboard-primary">Submit Deposit</button>
                <a href="{{ route('user.deposits.index') }}" class="dashboard-action" data-click-sound="true">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
