@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Liquidity Management</h1>

<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-1 bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Adjust Liquidity</h2>

        <form method="POST" action="{{ route('admin.liquidity.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 font-medium">Currency</label>
                <select name="currency_id" class="w-full border rounded-lg px-4 py-3" required>
                    <option value="">Select currency</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Action</label>
                <select name="action_type" class="w-full border rounded-lg px-4 py-3" required>
                    <option value="add">Add</option>
                    <option value="deduct">Deduct</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Amount</label>
                <input type="number" step="0.00000001" name="amount" class="w-full border rounded-lg px-4 py-3" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Note</label>
                <textarea name="note" rows="3" class="w-full border rounded-lg px-4 py-3"></textarea>
            </div>

            <button type="submit" class="w-full px-6 py-3 rounded-lg bg-black text-white">Save</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6 overflow-x-auto">
        <h2 class="text-xl font-semibold mb-4">Current Liquidity</h2>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Currency</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Reserved</th>
                    <th class="py-3">Available</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr class="border-b">
                        <td class="py-3">{{ $account->currency->code }}</td>
                        <td class="py-3">{{ number_format((float)$account->total_balance, 8) }}</td>
                        <td class="py-3">{{ number_format((float)$account->reserved_balance, 8) }}</td>
                        <td class="py-3">{{ number_format((float)$account->available_balance, 8) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-gray-500">No liquidity accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $accounts->links() }}
        </div>
    </div>
</div>
@endsection
