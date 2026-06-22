@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Admin Users</h1>

<div class="mb-6">
    <a href="{{ route('admin.admin-users.create') }}" class="px-5 py-3 rounded-lg bg-black text-white">New Admin</a>
</div>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Name</th>
                <th class="py-3">Email</th>
                <th class="py-3">Role</th>
                <th class="py-3">Status</th>
                <th class="py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($admins as $admin)
                <tr class="border-b">
                    <td class="py-3">{{ $admin->name }}</td>
                    <td class="py-3">{{ $admin->email }}</td>
                    <td class="py-3">{{ $admin->getRoleNames()->first() ?: $admin->role }}</td>
                    <td class="py-3">{{ ucfirst($admin->status) }}</td>
                    <td class="py-3">
                        <a href="{{ route('admin.admin-users.edit', $admin) }}" class="text-blue-600">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-4 text-gray-500">No admin users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $admins->links() }}
    </div>
</div>
@endsection
