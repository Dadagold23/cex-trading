@extends('layouts.user')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6" data-aos="fade-up">
    <div>
        <h1 class="text-3xl font-bold">Withdrawals</h1>
        <p class="text-gray-500 mt-1">Track your withdrawal requests and status.</p>
    </div>

    <a href="{{ route('user.withdrawals.create') }}" class="dashboard-primary" data-click-sound="true">New Withdrawal</a>
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
                    <th class="py-3">Fee</th>
                    <th class="py-3">Net</th>
                    <th class="py-3">Method</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $withdrawal)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3">{{ $withdrawal->reference }}</td>
                        <td class="py-3">{{ $withdrawal->currency->code }}</td>
                        <td class="py-3">{{ number_format((float) $withdrawal->amount, 8) }}</td>
                        <td class="py-3">{{ number_format((float) $withdrawal->fee, 8) }}</td>
                        <td class="py-3">{{ number_format((float) $withdrawal->net_amount, 8) }}</td>
                        <td class="py-3">{{ ucwords(str_replace('_', ' ', $withdrawal->method)) }}</td>
                        <td class="py-3">{{ ucfirst($withdrawal->status) }}</td>
                        <td class="py-3">{{ $withdrawal->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('user.withdrawals.show', $withdrawal) }}" class="text-slate-700 hover:underline" data-click-sound="true">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-gray-500">No withdrawals found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection
