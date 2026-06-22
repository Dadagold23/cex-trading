@extends('layouts.user')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Deposit Details</h1>
    <p class="text-gray-500 mt-1">Review your deposit request, status, and confirmation trail.</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 dashboard-card" data-aos="fade-right">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Reference</p>
                <p class="font-semibold">{{ $deposit->reference }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-semibold">{{ ucfirst($deposit->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Currency</p>
                <p class="font-semibold">{{ $deposit->currency->name }} ({{ $deposit->currency->code }})</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Amount</p>
                <p class="font-semibold">{{ number_format((float) $deposit->amount, 8) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Method</p>
                <p class="font-semibold">{{ ucwords(str_replace('_', ' ', $deposit->method)) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Network</p>
                <p class="font-semibold">{{ $deposit->network ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Transaction Hash</p>
                @if($deposit->transaction_hash)
                    <p class="font-mono text-sm break-all">{{ $deposit->transaction_hash }}</p>
                    @if($deposit->explorer_url)
                        <a href="{{ $deposit->explorer_url }}" target="_blank" class="text-blue-600 underline text-sm mt-1 inline-block" data-click-sound="true">
                            View on Explorer
                        </a>
                    @endif
                @else
                    <p class="font-semibold">-</p>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-500">Gateway Reference</p>
                <p class="font-semibold">{{ $deposit->gateway_reference ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">From Wallet</p>
                <p class="font-mono text-sm break-all">{{ $deposit->from_wallet_address ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">To Wallet</p>
                <p class="font-mono text-sm break-all">{{ $deposit->to_wallet_address ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Reviewed By</p>
                <p class="font-semibold">{{ $deposit->reviewer?->name ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Reviewed At</p>
                <p class="font-semibold">{{ $deposit->reviewed_at?->format('Y-m-d H:i') ?: '-' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <p class="text-sm text-gray-500">Notes</p>
            <p>{{ $deposit->notes ?: '-' }}</p>
        </div>

        @if($deposit->rejection_reason)
            <div class="mt-6 rounded-2xl bg-red-50 border border-red-200 p-4">
                <p class="text-sm text-red-500">Rejection Reason</p>
                <p class="text-red-700">{{ $deposit->rejection_reason }}</p>
            </div>
        @endif

        @if($deposit->proof_file)
            <div class="mt-6">
                <a href="{{ asset('storage/' . $deposit->proof_file) }}" target="_blank" class="text-blue-600 underline" data-click-sound="true">
                    View Uploaded Proof
                </a>
            </div>
        @endif
    </div>

    <div class="space-y-6" data-aos="fade-left">
        <div class="dashboard-card">
            <h2 class="text-xl font-semibold mb-4">Status Timeline</h2>

            <div class="space-y-4">
                @forelse($deposit->statusLogs as $log)
                    <div class="border rounded-2xl p-4 bg-gray-50 animated-card">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold">{{ ucfirst($log->status) }}</p>
                                <p class="text-sm text-gray-500">
                                    Source: {{ ucfirst($log->source) }}
                                    @if($log->creator)
                                        | By: {{ $log->creator->name }}
                                    @endif
                                </p>
                            </div>
                            <p class="text-sm text-gray-400">{{ $log->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        @if($log->note)
                            <p class="mt-2 text-sm text-gray-700">{{ $log->note }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No status timeline available.</p>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card">
            <a href="{{ route('user.deposits.index') }}" class="dashboard-action inline-block" data-click-sound="true">Back to Deposits</a>
        </div>
    </div>
</div>
@endsection
