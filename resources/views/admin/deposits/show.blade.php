@extends('layouts.admin')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Deposit Review</h1>
    <p class="text-gray-500 mt-1">Inspect and confirm or reject this deposit request.</p>
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
                <p class="text-sm text-gray-500">User</p>
                <p class="font-semibold">{{ $deposit->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-semibold">{{ $deposit->user->email }}</p>
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

        <div class="mt-8">
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
    </div>

    <div class="dashboard-card" data-aos="fade-left" x-data="{ approveOpen: false, rejectOpen: false }">
    <h2 class="text-xl font-semibold mb-4">Action Panel</h2>

    @if($deposit->status === 'pending')
        <button type="button" @click="approveOpen = true" class="w-full dashboard-primary mb-4">
            Confirm Deposit
        </button>

        <button type="button" @click="rejectOpen = true" class="w-full dashboard-danger">
            Reject Deposit
        </button>

        <div x-show="approveOpen" x-cloak class="fixed inset-0 z-[9998]">
            <div class="absolute inset-0 bg-black/50" @click="approveOpen = false"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
                    <h3 class="text-xl font-semibold mb-3">Confirm Deposit Approval</h3>
                    <p class="text-gray-600 mb-4">This will credit the user's wallet.</p>

                    <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">Approval Note</label>
                            <textarea name="note" rows="3" class="form-input"></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="approveOpen = false" class="dashboard-action">Cancel</button>
                            <button type="submit" class="dashboard-primary">Approve Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-[9998]">
            <div class="absolute inset-0 bg-black/50" @click="rejectOpen = false"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
                    <h3 class="text-xl font-semibold mb-3">Confirm Deposit Rejection</h3>
                    <p class="text-gray-600 mb-4">Please provide a reason for rejecting this deposit.</p>

                    <form method="POST" action="{{ route('admin.deposits.reject', $deposit) }}" class="space-y-4">
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
            This deposit has already been processed.
        </div>
    @endif
</div>


        <div class="dashboard-card">
            <h3 class="font-semibold mb-3">Audit Summary</h3>
            <p class="text-sm text-gray-600">Submitted: {{ $deposit->created_at->format('Y-m-d H:i') }}</p>
            <p class="text-sm text-gray-600">Reviewed: {{ $deposit->reviewed_at?->format('Y-m-d H:i') ?: '-' }}</p>
            <p class="text-sm text-gray-600">Confirmed: {{ $deposit->confirmed_at?->format('Y-m-d H:i') ?: '-' }}</p>
        </div>
    </div>
</div>
@endsection
