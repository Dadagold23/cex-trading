@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Revenue Report</h1>

<div class="grid md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Trade Fees</p><p class="text-3xl font-bold">{{ number_format($revenue['trade_fees'], 2) }}</p></div>
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Withdrawal Fees</p><p class="text-3xl font-bold">{{ number_format($revenue['withdrawal_fees'], 2) }}</p></div>
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Total Revenue</p><p class="text-3xl font-bold">{{ number_format($revenue['total_revenue'], 2) }}</p></div>
</div>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <h2 class="text-xl font-semibold mb-4">Monthly Trade Fees</h2>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Month</th>
                <th class="py-3">Completed Trades</th>
                <th class="py-3">Trade Fees</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monthlyTrades as $month)
                <tr class="border-b">
                    <td class="py-3">{{ $month->month }}</td>
                    <td class="py-3">{{ $month->total_count }}</td>
                    <td class="py-3">{{ number_format((float) $month->total_fee, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="py-4 text-gray-500">No revenue data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
