@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">User Crypto Wallet Addresses</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">User</th>
                <th class="py-3">Email</th>
                <th class="py-3">Currency</th>
                <th class="py-3">Network</th>
                <th class="py-3">Address</th>
                <th class="py-3">Status</th>
                <th class="py-3">Last Regenerated</th>
                <th class="py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wallets as $wallet)
                <tr class="border-b align-top">
                    <td class="py-3">{{ $wallet->user->name }}</td>
                    <td class="py-3">{{ $wallet->user->email }}</td>
                    <td class="py-3">{{ $wallet->currency->code }}</td>
                    <td class="py-3">{{ $wallet->network ?: '-' }}</td>
                    <td class="py-3">
                        <div class="max-w-xs break-all font-mono text-sm">
                            {{ $wallet->address }}
                        </div>
                    </td>
                    <td class="py-3">{{ $wallet->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="py-3">{{ $wallet->last_regenerated_at?->format('Y-m-d H:i') ?: '-' }}</td>
                    <td class="py-3">
                        <form method="POST" action="{{ route('admin.crypto-wallets.rotate', $wallet) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-800">
                                Rotate
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-4 text-gray-500">No user crypto wallet addresses found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $wallets->links() }}
    </div>
</div>
@endsection
