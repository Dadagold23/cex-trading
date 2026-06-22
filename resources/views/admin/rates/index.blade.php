@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Rates</h1>

<div class="mb-6">
    <a href="{{ route('admin.rates.create') }}" class="px-5 py-3 rounded-lg bg-black text-white">Add Rate</a>
</div>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Coin</th>
                <th class="py-3">Base</th>
                <th class="py-3">Buy Rate</th>
                <th class="py-3">Sell Rate</th>
                <th class="py-3">Buy Fee</th>
                <th class="py-3">Sell Fee</th>
                <th class="py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rates as $rate)
                <tr class="border-b">
                    <td class="py-3">{{ $rate->currency->code }}</td>
                    <td class="py-3">{{ $rate->baseCurrency->code }}</td>
                    <td class="py-3">{{ number_format((float)$rate->buy_rate, 2) }}</td>
                    <td class="py-3">{{ number_format((float)$rate->sell_rate, 2) }}</td>
                    <td class="py-3">{{ number_format((float)$rate->buy_fee, 2) }}</td>
                    <td class="py-3">{{ number_format((float)$rate->sell_fee, 2) }}</td>
                    <td class="py-3">{{ $rate->is_active ? 'Active' : 'Inactive' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-4 text-gray-500">No rates created yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $rates->links() }}
    </div>
</div>
@endsection
