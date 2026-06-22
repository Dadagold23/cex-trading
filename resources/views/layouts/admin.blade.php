<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'Coin Trading Platform') }}</title>
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

                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-black text-white flex items-center justify-center font-bold">
                                A
                            </div>
                            <div>
                                <p class="font-bold leading-none">Admin Dashboard</p>
                                <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->name }}</p>
                            </div>
                        </a>
                    </div>

                    <div class="hidden md:flex items-center gap-3">
                        <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                            Pending Deposits
                        </a>
                        <a href="{{ route('admin.support.index') }}" class="px-4 py-2 rounded-xl border hover:bg-gray-50">
                            Support
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
            <aside class="hidden lg:block w-80 bg-black text-white min-h-[calc(100vh-64px)] p-6">
                <h2 class="text-lg font-bold mb-6">Admin Navigation</h2>

                <nav class="space-y-2 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Dashboard</a>
                    <a href="{{ route('admin.trades.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Trades</a>
                    <a href="{{ route('admin.rates.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Rates</a>
                    <a href="{{ route('admin.deposits.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Deposits</a>
                    <a href="{{ route('admin.withdrawals.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Withdrawals</a>
                    <a href="{{ route('admin.kyc.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">KYC</a>
                    <a href="{{ route('admin.crypto-wallets.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Crypto Wallets</a>
                    <a href="{{ route('admin.deposit-monitoring.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Deposit Monitoring</a>
                    <a href="{{ route('admin.liquidity.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Liquidity</a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Audit Logs</a>
                    <a href="{{ route('admin.support.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Support</a>
                    <a href="{{ route('admin.reports.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Reports</a>
                    <a href="{{ route('admin.admin-users.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Admin Users</a>
                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Settings</a>
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

                <aside class="absolute left-0 top-0 h-full w-80 bg-black text-white p-6 overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold">Admin Menu</h2>
                        <button @click="sidebarOpen = false" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Dashboard</a>
                        <a href="{{ route('admin.trades.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Trades</a>
                        <a href="{{ route('admin.rates.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Rates</a>
                        <a href="{{ route('admin.deposits.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Deposits</a>
                        <a href="{{ route('admin.withdrawals.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Withdrawals</a>
                        <a href="{{ route('admin.kyc.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">KYC</a>
                        <a href="{{ route('admin.crypto-wallets.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Crypto Wallets</a>
                        <a href="{{ route('admin.deposit-monitoring.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Deposit Monitoring</a>
                        <a href="{{ route('admin.liquidity.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Liquidity</a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Audit Logs</a>
                        <a href="{{ route('admin.support.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Support</a>
                        <a href="{{ route('admin.reports.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Reports</a>
                        <a href="{{ route('admin.admin-users.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Admin Users</a>
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10">Settings</a>
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
