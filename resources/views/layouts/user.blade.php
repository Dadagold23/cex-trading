<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - {{ config('app.name', 'Coin Trading Platform') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <header class="bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="h-16 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button
                            @click="sidebarOpen = true"
                            class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-200 bg-white"
                            type="button"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">
                                U
                            </div>
                            <div>
                                <p class="font-bold leading-none">User Dashboard</p>
                                <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->name }}</p>
                            </div>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center gap-3">
                        <a href="{{ route('user.notifications.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                            Notifications
                        </a>
                        <a href="{{ route('user.profile.edit') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex">
            <aside class="hidden lg:block w-72 bg-slate-900 text-white min-h-[calc(100vh-64px)] p-6">
                <h2 class="text-lg font-bold mb-6">Navigation</h2>

                <nav class="space-y-2 text-sm">
                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('user.wallets.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Wallets</a>
                    <a href="{{ route('user.crypto-wallets.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Crypto Wallets</a>
                    <a href="{{ route('user.transactions.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Transactions</a>
                    <a href="{{ route('user.trades.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">My Trades</a>
                    <a href="{{ route('user.trades.buy') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Buy Coin</a>
                    <a href="{{ route('user.trades.sell') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Sell Coin</a>
                    <a href="{{ route('user.deposits.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Deposits</a>
                    <a href="{{ route('user.withdrawals.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Withdrawals</a>
                    <a href="{{ route('user.bank-accounts.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Bank Accounts</a>
                    <a href="{{ route('user.kyc.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">KYC</a>
                    <a href="{{ route('user.notifications.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Notifications</a>
                    <a href="{{ route('user.profile.edit') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Profile</a>
                    <a href="{{ route('user.security.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Security</a>
                    <a href="{{ route('user.support.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Support</a>
                </nav>

                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700">
                        Logout
                    </button>
                </form>
            </aside>

            <div class="fixed inset-0 z-50 lg:hidden" x-show="sidebarOpen" x-cloak>
                <div class="absolute inset-0 bg-black/50" @click="sidebarOpen = false"></div>

                <aside class="absolute left-0 top-0 h-full w-80 bg-slate-900 text-white p-6 overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold">User Menu</h2>
                        <button @click="sidebarOpen = false" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Dashboard</a>
                        <a href="{{ route('user.wallets.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Wallets</a>
                        <a href="{{ route('user.crypto-wallets.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Crypto Wallets</a>
                        <a href="{{ route('user.transactions.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Transactions</a>
                        <a href="{{ route('user.trades.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">My Trades</a>
                        <a href="{{ route('user.trades.buy') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Buy Coin</a>
                        <a href="{{ route('user.trades.sell') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Sell Coin</a>
                        <a href="{{ route('user.deposits.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Deposits</a>
                        <a href="{{ route('user.withdrawals.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Withdrawals</a>
                        <a href="{{ route('user.bank-accounts.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Bank Accounts</a>
                        <a href="{{ route('user.kyc.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">KYC</a>
                        <a href="{{ route('user.notifications.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Notifications</a>
                        <a href="{{ route('user.profile.edit') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Profile</a>
                        <a href="{{ route('user.security.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Security</a>
                        <a href="{{ route('user.support.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Support</a>
                        <a href="{{ route('user.two-factor.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Two-Factor Auth</a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="mt-8">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                </aside>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>

        @include('partials.footer')
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
