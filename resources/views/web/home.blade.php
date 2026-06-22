@extends('layouts.guest')

@section('content')
<div class="relative overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/coins/hero-bg.jpg') }}" alt="Crypto trading background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-slate-950/80"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 py-20 lg:py-28 text-white">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 border border-white/10 text-sm mb-6">
                    Secure Centralized Coin Trading Platform
                </span>

                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                    Buy and Sell
                    <span class="text-emerald-400">BTC, USDT, ETH</span>
                    and More with Admin Liquidity
                </h1>

                <p class="text-lg text-slate-300 mb-8 leading-8 max-w-2xl">
                    Trade digital assets on a secure admin-controlled platform with transparent rates,
                    wallet management, KYC verification, deposit confirmation, and safe withdrawals.
                </p>

                <div class="flex flex-wrap gap-4">
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl bg-emerald-500 text-slate-950 font-semibold hover:bg-emerald-400 animated-button">
                            Get Started
                        </a>
                    @endif

                    <a href="{{ route('rates') }}" class="px-6 py-3 rounded-xl border border-white/20 hover:bg-white/10 animated-button">
                        View Rates
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-10">
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/10 animated-card" data-aos="zoom-in" data-aos-delay="100">
                        <p class="text-sm text-slate-300">Supported Assets</p>
                        <p class="text-2xl font-bold mt-1">{{ $currencies->count() }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/10 animated-card" data-aos="zoom-in" data-aos-delay="200">
                        <p class="text-sm text-slate-300">Active Rates</p>
                        <p class="text-2xl font-bold mt-1">{{ $rates->count() }}</p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-4 border border-white/10 animated-card" data-aos="zoom-in" data-aos-delay="300">
                        <p class="text-sm text-slate-300">Mode</p>
                        <p class="text-lg font-bold mt-2">Admin-CEX</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-5" data-aos="fade-left">
                <div class="glass-card rounded-3xl p-6 animated-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/coins/btc.png') }}" alt="BTC" class="w-14 h-14 object-contain">
                        <div>
                            <p class="text-sm text-slate-300">Bitcoin</p>
                            <h3 class="text-2xl font-bold">BTC</h3>
                        </div>
                    </div>
                    <p class="mt-4 text-slate-300 text-sm">
                        Secure Bitcoin buy and sell requests with admin approval workflow.
                    </p>
                </div>

                <div class="glass-card rounded-3xl p-6 animated-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/coins/usdt.png') }}" alt="USDT" class="w-14 h-14 object-contain">
                        <div>
                            <p class="text-sm text-slate-300">Tether</p>
                            <h3 class="text-2xl font-bold">USDT</h3>
                        </div>
                    </div>
                    <p class="mt-4 text-slate-300 text-sm">
                        Stablecoin deposits, wallet addresses, and transaction hash confirmation.
                    </p>
                </div>

                <div class="glass-card rounded-3xl p-6 animated-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/coins/eth.png') }}" alt="ETH" class="w-14 h-14 object-contain">
                        <div>
                            <p class="text-sm text-slate-300">Ethereum</p>
                            <h3 class="text-2xl font-bold">ETH</h3>
                        </div>
                    </div>
                    <p class="mt-4 text-slate-300 text-sm">
                        Ethereum trading, network-aware deposits, and secure portfolio tracking.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
        <h2 class="section-title mb-4">Why Trade With Us</h2>
        <p class="section-subtitle">
            Built for secure admin-managed coin trading with verification, monitoring, and a smoother deposit workflow.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-3xl shadow p-6 animated-card" data-aos="fade-up" data-aos-delay="100">
            <h3 class="text-xl font-semibold mb-3">Transparent Rates</h3>
            <p class="text-gray-600 leading-7">
                Buy and sell rates are managed by the admin and displayed clearly for every supported asset.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow p-6 animated-card" data-aos="fade-up" data-aos-delay="200">
            <h3 class="text-xl font-semibold mb-3">Secure Wallets</h3>
            <p class="text-gray-600 leading-7">
                Each user gets crypto wallet addresses for supported coins with deposit proof and transaction tracking.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow p-6 animated-card" data-aos="fade-up" data-aos-delay="300">
            <h3 class="text-xl font-semibold mb-3">Verified Transactions</h3>
            <p class="text-gray-600 leading-7">
                KYC, admin approval, audit trail, and confirmation flow help keep trading controlled and secure.
            </p>
        </div>
    </div>

    <div class="relative rounded-3xl overflow-hidden" data-aos="fade-up">
        @if(file_exists(public_path('images/coins/chart-bg.jpg')))
            <img src="{{ asset('images/coins/chart-bg.jpg') }}" alt="Trading chart" class="absolute inset-0 w-full h-full object-cover opacity-10">
        @endif

        <div class="relative bg-white rounded-3xl shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold">Latest Rates</h2>
                <a href="{{ route('rates') }}" class="text-sm text-slate-700 hover:underline">See all</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3">Coin</th>
                            <th class="py-3">Base</th>
                            <th class="py-3">Buy Rate</th>
                            <th class="py-3">Sell Rate</th>
                            <th class="py-3">Min</th>
                            <th class="py-3">Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rates as $rate)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="py-3">{{ $rate->currency->code }}</td>
                                <td class="py-3">{{ $rate->baseCurrency->code }}</td>
                                <td class="py-3">{{ number_format((float) $rate->buy_rate, 2) }}</td>
                                <td class="py-3">{{ number_format((float) $rate->sell_rate, 2) }}</td>
                                <td class="py-3">{{ number_format((float) $rate->min_amount, 8) }}</td>
                                <td class="py-3">{{ $rate->max_amount !== null ? number_format((float) $rate->max_amount, 8) : 'Unlimited' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-gray-500">No active rates available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
