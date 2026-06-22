@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Users Report</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Name</th>
                <th class="py-3">Email</th>
                <th class="py-3">Deposits</th>
                <th class="py-3">Withdrawals</th>
                <th class="py-3">Trades</th>
                <th class="py-3">Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-b">
                    <td class="py-3">{{ $user->name }}</td>
                    <td class="py-3">{{ $user->email }}</td>
                    <td class="py-3">{{ $user->deposits_count }}</td>
                    <td class="py-3">{{ $user->withdrawals_count }}</td>
                    <td class="py-3">{{ $user->trade_orders_count }}</td>
                    <td class="py-3">{{ $user->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
