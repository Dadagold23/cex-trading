@extends('layouts.user')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6" data-aos="fade-up">
    <div>
        <h1 class="text-3xl font-bold">My Trades</h1>
        <p class="text-gray-500 mt-1">Track your buy and sell orders.</p>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('user.trades.buy') }}" class="dashboard-primary" data-click-sound="true">Buy Coin</a>
        <a href="{{ route('user.trades.sell') }}" class="dashboard-action" data-click-sound="true">Sell Coin</a>
    </div>
</div>

<div class="dashboard-card mb-6" data-aos="fade-up" data-aos-delay="100">
    <form method="GET" action="{{ url()->current() }}" class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block mb-2 text-sm font-medium">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Reference, subject, status">
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Status</label>
            <input type="text" name="status" value="{{ request('status') }}" class="form-input" placeholder="pending / approved / rejected">
        </div>

        <div class="flex items-end gap-3">
            <button type="submit" class="dashboard-primary">Filter</button>
            <a href="{{ url()->current() }}" class="dashboard-action" data-click-sound="true">Reset</a>
        </div>
    </form>
</div>

<div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Reference</th>
                    <th class="py-3">Type</th>
                    <th class="py-3">Coin</th>
                    <th class="py-3">Base</th>
                    <th class="py-3">Amount</th>
                    <th class="py-3">Rate</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trades as $trade)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3">{{ $trade->reference }}</td>
                        <td class="py-3">
                            <span class="px-3 py-1 rounded-full text-xs {{ $trade->order_type === 'buy' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($trade->order_type) }}
                            </span>
                        </td>
                        <td class="py-3">{{ $trade->currency->code }}</td>
                        <td class="py-3">{{ $trade->baseCurrency->code }}</td>
                        <td class="py-3">{{ number_format((float) $trade->amount, 8) }}</td>
                        <td class="py-3">{{ number_format((float) $trade->rate, 2) }}</td>
                        <td class="py-3">{{ number_format((float) $trade->total, 2) }}</td>
                        <td class="py-3">{{ ucfirst($trade->status) }}</td>
                        <td class="py-3">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('user.trades.show', $trade) }}" class="text-slate-700 hover:underline" data-click-sound="true">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-6 text-gray-500">No trade orders found.</td>
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
