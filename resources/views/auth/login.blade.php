@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gray-50">
    <div class="w-full max-w-md bg-white shadow rounded-2xl p-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">Login</h1>

        @include('partials.alerts')

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-slate-700 hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-3 text-white font-medium hover:bg-slate-800">
                Login
            </button>
        </form>
    </div>
</div>
@endsection