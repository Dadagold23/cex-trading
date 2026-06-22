@extends('layouts.admin')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Withdrawal Review</h1>
    <p class="text-gray-500 mt-1">Approve or reject this withdrawal request.</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 dashboard-card" data-aos="fade-right">
        <div class="grid md:grid-cols-2 gap-6">
            <div><p class="text-sm text-gray-500">Reference</p><p class="font-semibold">{{ $withdrawal->reference }}</p></div>
            <div><p class="text-sm text-gray-500">Status</p><p class="font-semibold">{{ ucfirst($withdrawal->status) }}</p></div>
            <div><p class="text-sm text-gray-500">User</p><p class="font-semibold">{{ $withdrawal->user->name }}</p></div>
            <div><p class="text-sm text-gray-500">Email</p><p class="font-semibold">{{ $withdrawal->user->email }}</p></div>
            <div><p class="text-sm text-gray-500">Currency</p><p class="font-semibold">{{ $withdrawal->currency->name }} ({{ $withdrawal->currency->code }})</p></div>
            <div><p class="text-sm text-gray-500">Amount</p><p class="font-semibold">{{ number_format((float) $withdrawal->amount, 8) }}</p></div>
            <div><p class="text-sm text-gray-500">Fee</p><p class="font-semibold">{{ number_format((float) $withdrawal->fee, 8) }}</p></div>
            <div><p class="text-sm text-gray-500">Net Amount</p><p class="font-semibold">{{ number_format((float) $withdrawal->net_amount, 8) }}</p></div>
            <div><p class="text-sm text-gray-500">Method</p><p class="font-semibold">{{ ucwords(str_replace('_', ' ', $withdrawal->method)) }}</p></div>
            <div><p class="text-sm text-gray-500">Destination Type</p><p class="font-semibold">{{ ucwords(str_replace('_', ' ', $withdrawal->destination_type)) }}</p></div>
        </div>

        <div class="mt-6">
            <p class="text-sm text-gray-500">Destination Details</p>
            <pre class="bg-gray-50 rounded-2xl p-4 text-sm overflow-x-auto">{{ json_encode($withdrawal->destination_details, JSON_PRETTY_PRINT) }}</pre>
        </div>

        @if($withdrawal->rejection_reason)
            <div class="mt-6 rounded-2xl bg-red-50 border border-red-200 p-4">
                <p class="text-sm text-red-500">Rejection Reason</p>
                <p class="text-red-700">{{ $withdrawal->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <div class="dashboard-card" data-aos="fade-left" x-data="{ approveOpen: false, rejectOpen: false }">
    <h2 class="text-xl font-semibold mb-4">Action Panel</h2>

    @if($withdrawal->status === 'pending')
        <button type="button" @click="approveOpen = true" class="w-full dashboard-primary mb-4">
            Approve Withdrawal
        </button>

        <button type="button" @click="rejectOpen = true" class="w-full dashboard-danger">
            Reject Withdrawal
        </button>

        <div x-show="approveOpen" x-cloak class="fixed inset-0 z-[9998]">
            <div class="absolute inset-0 bg-black/50" @click="approveOpen = false"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
                    <h3 class="text-xl font-semibold mb-3">Approve Withdrawal</h3>
                    <p class="text-gray-600 mb-4">Confirm that this withdrawal has been processed.</p>

                    <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" class="flex justify-end gap-3">
                        @csrf
                        <button type="button" @click="approveOpen = false" class="dashboard-action">Cancel</button>
                        <button type="submit" class="dashboard-primary">Approve Now</button>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-[9998]">
            <div class="absolute inset-0 bg-black/50" @click="rejectOpen = false"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
                    <h3 class="text-xl font-semibold mb-3">Reject Withdrawal</h3>

                    <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">Rejection Reason</label>
                            <textarea name="reason" rows="3" class="form-input" required></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="rejectOpen = false" class="dashboard-action">Cancel</button>
                            <button type="submit" class="dashboard-danger">Reject Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-2xl bg-gray-50 border p-4 text-gray-600">
            This withdrawal has already been processed.
        </div>
    @endif
</div>

</div>
@endsection
