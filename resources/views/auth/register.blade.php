@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gray-50">
    <div class="w-full max-w-lg bg-white shadow rounded-2xl p-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Register</h1>

        @include('partials.alerts')

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-3 text-white font-medium hover:bg-slate-800">
                Register
            </button>
        </form>
    </div>
</div>
@endsection