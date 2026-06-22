@extends('layouts.user')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6" data-aos="fade-up">
    <div>
        <h1 class="text-3xl font-bold">Deposits</h1>
        <p class="text-gray-500 mt-1">Manage your fiat and crypto deposit requests.</p>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('user.deposits.create') }}" class="dashboard-primary" data-click-sound="true">New Deposit</a>
        <a href="{{ route('user.crypto-wallets.index') }}" class="dashboard-action" data-click-sound="true">Crypto Wallets</a>
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
                    <th class="py-3">Currency</th>
                    <th class="py-3">Amount</th>
                    <th class="py-3">Method</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $deposit)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3">{{ $deposit->reference }}</td>
                        <td class="py-3">{{ $deposit->currency->code }}</td>
                        <td class="py-3">{{ number_format((float) $deposit->amount, 8) }}</td>
                        <td class="py-3">{{ ucwords(str_replace('_', ' ', $deposit->method)) }}</td>
                        <td class="py-3">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $deposit->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $deposit->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $deposit->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </td>
                        <td class="py-3">{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('user.deposits.show', $deposit) }}" class="text-slate-700 hover:underline" data-click-sound="true">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-gray-500">No deposits found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $deposits->links() }}
    </div>
</div>
@endsection
