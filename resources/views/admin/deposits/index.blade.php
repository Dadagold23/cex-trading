@extends('layouts.admin')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">Deposits</h1>
    <p class="text-gray-500 mt-1">Monitor and confirm incoming deposit requests.</p>
</div>

<div class="dashboard-card mb-6" data-aos="fade-up" data-aos-delay="100">
    <form method="GET" action="{{ route('admin.deposits.index') }}" class="grid md:grid-cols-5 gap-4">
        <div>
            <label class="block mb-2 text-sm font-medium">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Reference, user, email, tx hash">
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Status</label>
            <select name="status" class="form-input">
                <option value="">All</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Method</label>
            <select name="method" class="form-input">
                <option value="">All</option>
                <option value="bank_transfer" @selected(request('method') === 'bank_transfer')>Bank Transfer</option>
                <option value="crypto_transfer" @selected(request('method') === 'crypto_transfer')>Crypto Transfer</option>
                <option value="gateway" @selected(request('method') === 'gateway')>Gateway</option>
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
            <a href="{{ route('admin.deposits.index') }}" class="dashboard-action" data-click-sound="true">Reset</a>
            <a href="{{ route('admin.deposits.export', request()->query()) }}" class="dashboard-action" data-click-sound="true">Export CSV</a>
        </div>
    </form>
</div>


<div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Reference</th>
                    <th class="py-3">User</th>
                    <th class="py-3">Currency</th>
                    <th class="py-3">Amount</th>
                    <th class="py-3">Method</th>
                    <th class="py-3">TX Hash</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $deposit)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3">{{ $deposit->reference }}</td>
                        <td class="py-3">{{ $deposit->user->name }}</td>
                        <td class="py-3">{{ $deposit->currency->code }}</td>
                        <td class="py-3">{{ number_format((float) $deposit->amount, 8) }}</td>
                        <td class="py-3">{{ ucwords(str_replace('_', ' ', $deposit->method)) }}</td>
                        <td class="py-3">
                            @if($deposit->transaction_hash)
                                <div class="max-w-[180px] break-all font-mono text-xs">
                                    {{ $deposit->transaction_hash }}
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-3">{{ ucfirst($deposit->status) }}</td>
                        <td class="py-3">{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('admin.deposits.show', $deposit) }}" class="text-slate-700 hover:underline" data-click-sound="true">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-6 text-gray-500">No deposit records found.</td>
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
