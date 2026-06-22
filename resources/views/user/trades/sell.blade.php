@extends('layouts.user')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6" data-aos="fade-up">
        <h1 class="text-3xl font-bold">Sell Coin</h1>
        <p class="text-gray-500 mt-1">Create a sell order from your available balance.</p>
    </div>

    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
        <form method="POST" action="{{ route('user.trades.store-sell') }}" class="grid md:grid-cols-2 gap-6">
            @csrf

            <div>
                <label class="block mb-2 font-medium">Coin</label>
                <select name="currency_id" class="w-full border rounded-xl px-4 py-3" required>
                    <option value="">Select coin</option>
                    @foreach($rates as $rate)
                        <option value="{{ $rate->currency_id }}" @selected(old('currency_id') == $rate->currency_id)>
                            {{ $rate->currency->name }} ({{ $rate->currency->code }}) - {{ $rate->baseCurrency->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium">Amount</label>
                <input type="number" step="0.00000001" name="amount" value="{{ old('amount') }}" class="w-full border rounded-xl px-4 py-3" required>
            </div>

            <div class="md:col-span-2">
                <label class="block mb-2 font-medium">Notes</label>
                <textarea name="notes" rows="4" class="w-full border rounded-xl px-4 py-3">{{ old('notes') }}</textarea>
            </div>

            <div class="md:col-span-2 flex flex-wrap gap-3">
                <button type="submit" class="dashboard-primary">Submit Sell Order</button>
                <a href="{{ route('user.trades.index') }}" class="dashboard-action" data-click-sound="true">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection
