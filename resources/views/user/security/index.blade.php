@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Security Settings</h1>

<div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('user.security.update') }}" class="bg-white rounded-2xl shadow p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-xl font-semibold mb-4">Change Password</h2>

        <div class="mb-4">
            <label class="block mb-2 font-medium">Current Password</label>
            <input type="password" name="current_password" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-medium">New Password</label>
            <input type="password" name="password" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-medium">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-4 py-3">
        </div>

        <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Update Password</button>
    </form>

    <form method="POST" action="{{ route('user.security.update') }}" class="bg-white rounded-2xl shadow p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-xl font-semibold mb-4">Set Withdrawal PIN</h2>

        <div class="mb-4">
            <label class="block mb-2 font-medium">4-Digit PIN</label>
            <input type="password" name="withdrawal_pin" maxlength="4" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div class="mb-4">
            <label class="block mb-2 font-medium">Confirm PIN</label>
            <input type="password" name="withdrawal_pin_confirmation" maxlength="4" class="w-full border rounded-lg px-4 py-3">
        </div>

        <button type="submit" class="px-6 py-3 rounded-lg bg-black text-white">Save PIN</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow p-6 mt-6">
    <h2 class="text-xl font-semibold mb-4">Recent Login Activity</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">IP Address</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loginLogs as $log)
                    <tr class="border-b">
                        <td class="py-3">{{ $log->ip_address ?: '-' }}</td>
                        <td class="py-3">{{ ucfirst($log->login_status) }}</td>
                        <td class="py-3">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-gray-500">No login activity found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
