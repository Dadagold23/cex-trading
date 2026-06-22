<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CEX - Trading Platform') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">
    <div x-data="{ open: false }" class="min-h-screen flex flex-col">
        <nav class="bg-white/95 backdrop-blur border-b sticky top-0 z-50 shadow-sm" data-aos="fade-down">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('home') }}" class="flex items-center gap-3" CEX - Trading  Platforrm-sound="true">
                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold">
                            C
                        </div>
                        <div>
                            <p class="text-base font-bold leading-none">Coin Trading</p>
                            <p class="text-xs text-gray-500 leading-none mt-1">Admin-CEX Platform</p>
                        </div>
                    </a>

                    <div class="hidden lg:flex items-center gap-6 text-sm font-medium">
                        <a href="{{ route('about') }}" class="text-gray-600 hover:text-slate-900 transition" data-click-sound="true">About</a>
                        <a href="{{ route('rates') }}" class="text-gray-600 hover:text-slate-900 transition" data-click-sound="true">Rates</a>
                        <a href="{{ route('how-it-works') }}" class="text-gray-600 hover:text-slate-900 transition" data-click-sound="true">How It Works</a>
                        <a href="{{ route('contact') }}" class="text-gray-600 hover:text-slate-900 transition" data-click-sound="true">Contact</a>

                        @auth
                            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white hover:bg-slate-800 animated-button" data-click-sound="true">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('user.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white hover:bg-slate-800 animated-button" data-click-sound="true">
                                    Dashboard
                                </a>
                            @endif
                        @else
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-slate-900 transition" data-click-sound="true">Login</a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white hover:bg-slate-800 animated-button" data-click-sound="true">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>

                    <button
                        @click="open = !open"
                        class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl border border-gray-200 bg-white text-slate-900 animated-button"
                        type="button"
                    >
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="open" x-cloak class="lg:hidden border-t bg-white">
                <div class="px-4 py-4 space-y-2">
                    <a href="{{ route('about') }}" class="block px-4 py-3 rounded-xl hover:bg-gray-50" data-click-sound="true">About</a>
                    <a href="{{ route('rates') }}" class="block px-4 py-3 rounded-xl hover:bg-gray-50" data-click-sound="true">Rates</a>
                    <a href="{{ route('how-it-works') }}" class="block px-4 py-3 rounded-xl hover:bg-gray-50" data-click-sound="true">How It Works</a>
                    <a href="{{ route('contact') }}" class="block px-4 py-3 rounded-xl hover:bg-gray-50" data-click-sound="true">Contact</a>

                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl bg-slate-900 text-white" data-click-sound="true">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="block px-4 py-3 rounded-xl bg-slate-900 text-white" data-click-sound="true">
                                Dashboard
                            </a>
                        @endif
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl hover:bg-gray-50" data-click-sound="true">Login</a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-4 py-3 rounded-xl bg-slate-900 text-white" data-click-sound="true">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <main class="flex-1">
            @yield('content')
        </main>

        @include('partials.footer')
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
