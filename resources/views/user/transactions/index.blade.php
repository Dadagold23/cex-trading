@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Transaction History</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Date</th>
                <th class="py-3">Reference</th>
                <th class="py-3">Category</th>
                <th class="py-3">Type</th>
                <th class="py-3">Currency</th>
                <th class="py-3">Amount</th>
                <th class="py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr class="border-b">
                    <td class="py-3">{{ $transaction['date']->format('Y-m-d H:i') }}</td>
                    <td class="py-3">{{ $transaction['reference'] }}</td>
                    <td class="py-3">{{ ucfirst($transaction['category']) }}</td>
                    <td class="py-3">{{ $transaction['type'] }}</td>
                    <td class="py-3">{{ $transaction['currency'] }}</td>
                    <td class="py-3">{{ number_format((float)$transaction['amount'], 8) }}</td>
                    <td class="py-3">{{ ucfirst($transaction['status']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-4 text-gray-500">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
