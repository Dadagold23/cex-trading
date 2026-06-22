@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Bank Accounts</h1>

<div class="mb-6">
    <a href="{{ route('user.bank-accounts.create') }}" class="px-5 py-3 rounded-lg bg-slate-900 text-white">Add Bank Account</a>
</div>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Bank</th>
                <th class="py-3">Account Name</th>
                <th class="py-3">Account Number</th>
                <th class="py-3">Currency</th>
                <th class="py-3">Default</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bankAccounts as $bankAccount)
                <tr class="border-b">
                    <td class="py-3">{{ $bankAccount->bank_name }}</td>
                    <td class="py-3">{{ $bankAccount->account_name }}</td>
                    <td class="py-3">{{ $bankAccount->account_number }}</td>
                    <td class="py-3">{{ $bankAccount->currency_code }}</td>
                    <td class="py-3">{{ $bankAccount->is_default ? 'Yes' : 'No' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 text-gray-500">No bank accounts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $bankAccounts->links() }}
    </div>
</div>
@endsection
