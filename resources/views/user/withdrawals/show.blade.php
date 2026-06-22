@extends('layouts.user')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Withdrawal Details</h1>
    <p class="text-gray-500 mt-1">Review your withdrawal request and processing status.</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 dashboard-card" data-aos="fade-right">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Reference</p>
                <p class="font-semibold">{{ $withdrawal->reference }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-semibold">{{ ucfirst($withdrawal->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Currency</p>
                <p class="font-semibold">{{ $withdrawal->currency->name }} ({{ $withdrawal->currency->code }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Amount</p>
                <p class="font-semibold">{{ number_format((float) $withdrawal->amount, 8) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Fee</p>
                <p class="font-semibold">{{ number_format((float) $withdrawal->fee, 8) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Net Amount</p>
                <p class="font-semibold">{{ number_format((float) $withdrawal->net_amount, 8) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Method</p>
                <p class="font-semibold">{{ ucwords(str_replace('_', ' ', $withdrawal->method)) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Destination Type</p>
                <p class="font-semibold">{{ ucwords(str_replace('_', ' ', $withdrawal->destination_type)) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Processed At</p>
                <p class="font-semibold">{{ $withdrawal->processed_at?->format('Y-m-d H:i') ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Reviewed By</p>
                <p class="font-semibold">{{ $withdrawal->reviewer?->name ?: '-' }}</p>
            </div>
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

    <div class="dashboard-card" data-aos="fade-left">
        <a href="{{ route('user.withdrawals.index') }}" class="dashboard-action inline-block" data-click-sound="true">Back to Withdrawals</a>
    </div>
</div>
@endsection
