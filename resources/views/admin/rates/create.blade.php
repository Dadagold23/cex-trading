@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Create Rate</h1>

<form method="POST" action="{{ route('admin.rates.store') }}" class="bg-white rounded-2xl shadow p-6 max-w-3xl">
    @csrf

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 font-medium">Coin Currency</label>
            <select name="currency_id" class="w-full border rounded-lg px-4 py-3" required>
                <option value="">Select</option>
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">Base Currency</label>
            <select name="base_currency_id" class="w-full border rounded-lg px-4 py-3" required>
                <option value="">Select</option>
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">Buy Rate</label>
            <input type="number" step="0.00000001" name="buy_rate" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Sell Rate</label>
            <input type="number" step="0.00000001" name="sell_rate" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Buy Fee</label>
            <input type="number" step="0.00000001" name="buy_fee" class="w-full border rounded-lg px-4 py-3" value="0">
        </div>

        <div>
            <label class="block mb-2 font-medium">Sell Fee</label>
            <input type="number" step="0.00000001" name="sell_fee" class="w-full border rounded-lg px-4 py-3" value="0">
        </div>

        <div>
            <label class="block mb-2 font-medium">Min Amount</label>
            <input type="number" step="0.00000001" name="min_amount" class="w-full border rounded-lg px-4 py-3" value="0">
        </div>

        <div>
            <label class="block mb-2 font-medium">Max Amount</label>
            <input type="number" step="0.00000001" name="max_amount" class="w-full border rounded-lg px-4 py-3">
        </div>
    </div>

    <div class="mt-4">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" checked>
            <span>Active</span>
        </label>
    </div>

    <div class="mt-6">
        <button class="px-6 py-3 rounded-lg bg-black text-white">Save Rate</button>
    </div>
</form>
@endsection
