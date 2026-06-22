@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">My Profile</h1>

<form method="POST" action="{{ route('user.profile.update') }}" class="bg-white rounded-2xl shadow p-6 max-w-3xl">
    @csrf
    @method('PATCH')

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 font-medium">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded-lg px-4 py-3">
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Update Profile</button>
    </div>
</form>
@endsection
