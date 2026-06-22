@extends('layouts.guest')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold mb-6">How It Works</h1>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">1. Create an Account</h2>
            <p class="text-gray-600">Register and log in to access your wallet, profile, and transaction dashboard.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">2. Complete KYC</h2>
            <p class="text-gray-600">Submit your verification documents. KYC approval is required for secure withdrawals.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">3. Fund Your Account</h2>
            <p class="text-gray-600">Create a deposit request and wait for admin review and wallet funding.</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-xl font-semibold mb-3">4. Buy or Sell</h2>
            <p class="text-gray-600">Submit a trade order. The admin reviews and approves valid trades before settlement.</p>
        </div>
    </div>
</div>
@endsection
