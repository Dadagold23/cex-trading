@extends('layouts.admin')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Trade Queue</h1>
    <p class="text-gray-500 mt-1">Review and manage user trade orders.</p>
</div>

<div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Reference</th>
                    <th class="py-3">User</th>
                    <th class="py-3">Type</th>
                    <th class="py-3">Coin</th>
                    <th class="py-3">Base</th>
                    <th class="py-3">Amount</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>

            <div class="dashboard-card mb-6" data-aos="fade-up" data-aos-delay="100">
    <form method="GET" action="{{ route('admin.trades.index') }}" class="grid md:grid-cols-5 gap-4">
        <div>
            <label class="block mb-2 text-sm font-medium">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Reference, user, email">
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Status</label>
            <select name="status" class="form-input">
                <option value="">All</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Order Type</label>
            <select name="order_type" class="form-input">
                <option value="">All</option>
                <option value="buy" @selected(request('order_type') === 'buy')>Buy</option>
                <option value="sell" @selected(request('order_type') === 'sell')>Sell</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Currency</label>
            <select name="currency_id" class="form-input">
                <option value="">All</option>
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected((string)request('currency_id') === (string)$currency->id)>
                        {{ $currency->code }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-3">
            <button type="submit" class="dashboard-primary">Filter</button>
            <a href="{{ route('admin.trades.index') }}" class="dashboard-action" data-click-sound="true">Reset</a>
            <a href="{{ route('admin.trades.export', request()->query()) }}" class="dashboard-action" data-click-sound="true">Export CSV</a>
        </div>
    </form>
</div>


            <tbody>
                @forelse($trades as $trade)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3">{{ $trade->reference }}</td>
                        <td class="py-3">{{ $trade->user->name }}</td>
                        <td class="py-3">
                            <span class="px-3 py-1 rounded-full text-xs {{ $trade->order_type === 'buy' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($trade->order_type) }}
                            </span>
                        </td>
                        <td class="py-3">{{ $trade->currency->code }}</td>
                        <td class="py-3">{{ $trade->baseCurrency->code }}</td>
                        <td class="py-3">{{ number_format((float) $trade->amount, 8) }}</td>
                        <td class="py-3">{{ number_format((float) $trade->total, 2) }}</td>
                        <td class="py-3">{{ ucfirst($trade->status) }}</td>
                        <td class="py-3">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('admin.trades.show', $trade) }}" class="text-slate-700 hover:underline" data-click-sound="true">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-6 text-gray-500">No trades found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $trades->links() }}
    </div>
</div>
@endsection
