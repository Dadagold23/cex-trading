@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Wallets</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Currency</th>
                <th class="py-3">Code</th>
                <th class="py-3">Available Balance</th>
                <th class="py-3">Held Balance</th>
                <th class="py-3">Total Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wallets as $wallet)
                <tr class="border-b">
                    <td class="py-3">{{ $wallet->currency->name }}</td>
                    <td class="py-3">{{ $wallet->currency->code }}</td>
                    <td class="py-3">{{ number_format((float) $wallet->available_balance, 8) }}</td>
                    <td class="py-3">{{ number_format((float) $wallet->held_balance, 8) }}</td>
                    <td class="py-3">{{ number_format((float) ($wallet->available_balance + $wallet->held_balance), 8) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 text-gray-500">No wallets found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $wallets->links() }}
    </div>
</div>
@endsection
