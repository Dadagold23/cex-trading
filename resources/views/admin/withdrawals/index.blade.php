@extends('layouts.admin')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Withdrawals</h1>
    <p class="text-gray-500 mt-1">Review and manage user withdrawal requests.</p>
</div>

<div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Reference</th>
                    <th class="py-3">User</th>
                    <th class="py-3">Currency</th>
                    <th class="py-3">Amount</th>
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
                        <td class="py-3">{{ $withdrawal->user->name }}</td>
                        <td class="py-3">{{ $withdrawal->currency->code }}</td>
                        <td class="py-3">{{ number_format((float) $withdrawal->amount, 8) }}</td>
                        <td class="py-3">{{ number_format((float) $withdrawal->net_amount, 8) }}</td>
                        <td class="py-3">{{ ucwords(str_replace('_', ' ', $withdrawal->method)) }}</td>
                        <td class="py-3">{{ ucfirst($withdrawal->status) }}</td>
                        <td class="py-3">{{ $withdrawal->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="text-slate-700 hover:underline" data-click-sound="true">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-gray-500">No withdrawal records found.</td>
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
