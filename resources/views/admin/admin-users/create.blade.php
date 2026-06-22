@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Create Admin User</h1>

<form method="POST" action="{{ route('admin.admin-users.store') }}" class="bg-white rounded-2xl shadow p-6 max-w-3xl">
    @csrf

    <div class="grid md:grid-cols-2 gap-4">
        <div><label class="block mb-2 font-medium">Name</label><input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-3" required></div>
        <div><label class="block mb-2 font-medium">Username</label><input type="text" name="username" value="{{ old('username') }}" class="w-full border rounded-lg px-4 py-3"></div>
        <div><label class="block mb-2 font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-3" required></div>
        <div><label class="block mb-2 font-medium">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-3"></div>
        <div><label class="block mb-2 font-medium">Password</label><input type="password" name="password" class="w-full border rounded-lg px-4 py-3" required></div>

        <div>
            <label class="block mb-2 font-medium">Role</label>
            <select name="role" class="w-full border rounded-lg px-4 py-3" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">Status</label>
            <select name="status" class="w-full border rounded-lg px-4 py-3" required>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="px-6 py-3 rounded-lg bg-black text-white">Create Admin</button>
    </div>
</form>
@endsection
