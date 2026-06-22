@extends('layouts.guest')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gray-50">
    <div class="w-full max-w-md bg-white shadow rounded-2xl p-8" data-aos="fade-up">
        <h1 class="text-2xl font-bold mb-3 text-gray-900">Two-Factor Verification</h1>
        <p class="text-gray-600 mb-6">Enter the 6-digit code from your authenticator app or use a recovery code.</p>

        <form method="POST" action="{{ route('two-factor.challenge.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Authentication Code</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    required
                    autofocus
                    class="w-full rounded-xl border border-gray-300 px-4 py-3"
                    placeholder="123456 or recovery code"
                >
            </div>

            <button type="submit" class="w-full dashboard-primary">
                Verify and Continue
            </button>
        </form>
    </div>
</div>
@endsection
