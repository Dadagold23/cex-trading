@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Trades Report</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Date</th>
                <th class="py-3">Reference</th>
                <th class="py-3">User</th>
                <th class="py-3">Type</th>
                <th class="py-3">Currency</th>
                <th class="py-3">Amount</th>
                <th class="py-3">Fee</th>
                <th class="py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trades as $trade)
                <tr class="border-b">
                    <td class="py-3">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3">{{ $trade->reference }}</td>
                    <td class="py-3">{{ $trade->user->name }}</td>
                    <td class="py-3">{{ strtoupper($trade->order_type) }}</td>
                    <td class="py-3">{{ $trade->currency->code }}</td>
                    <td class="py-3">{{ number_format((float) $trade->amount, 8) }}</td>
                    <td class="py-3">{{ number_format((float) $trade->fee, 2) }}</td>
                    <td class="py-3">{{ ucfirst($trade->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6">{{ $trades->links() }}</div>
</div>
@endsection
