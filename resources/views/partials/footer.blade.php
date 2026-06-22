<footer class="bg-slate-950 text-gray-200 mt-16 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-10">

            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ config('app.name', 'CEX Trading') }}</h3>
                <p class="text-sm text-gray-400 leading-7">
                    A secure centralized trading platform for buying and selling crypto and digital assets
                    directly with admin-managed liquidity, transparent rates, wallet accounting, and
                    compliance workflows.
                </p>

                <div class="flex items-center gap-3 mt-5">
                    <span class="px-3 py-1 text-xs rounded-full bg-emerald-600/20 text-emerald-400 border border-emerald-700">
                        Secure Trading
                    </span>
                    <span class="px-3 py-1 text-xs rounded-full bg-blue-600/20 text-blue-400 border border-blue-700">
                        Admin Verified
                    </span>
                </div>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Company</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('rates') }}" class="hover:text-white transition">Rates</a></li>
                    <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition">How It Works</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Trading & Support</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    @if(Route::has('login'))
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                    @endif

                    @if(Route::has('register'))
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Create Account</a></li>
                    @endif

                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">Support Center</a></li>
                    <li><span>KYC Verification</span></li>
                    <li><span>Wallet Management</span></li>
                    <li><span>Trade Approval Workflow</span></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Contact & Legal</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li>Email: <span class="text-gray-300">support@yourdomain.com</span></li>
                    <li>Phone: <span class="text-gray-300">+234 XXX XXX XXXX</span></li>
                    <li>Hours: <span class="text-gray-300">24/7 Support</span></li>
                    <li>Location: <span class="text-gray-300">Nigeria</span></li>
                </ul>

                <div class="mt-5 flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm">f</a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm">x</a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm">in</a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm">tg</a>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4 mt-10">
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4">
                <p class="text-sm text-gray-400">Fast Settlement</p>
                <p class="text-white font-semibold mt-1">Quick admin-reviewed trade execution</p>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4">
                <p class="text-sm text-gray-400">Secure Withdrawals</p>
                <p class="text-white font-semibold mt-1">PIN-protected and KYC-backed withdrawals</p>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4">
                <p class="text-sm text-gray-400">Transparent Rates</p>
                <p class="text-white font-semibold mt-1">Admin-managed buy and sell pricing</p>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-gray-400">
            <p>© {{ date('Y') }} {{ config('app.name', 'CEX Trading') }}. All rights reserved.</p>

            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
                <a href="#" class="hover:text-white transition">Risk Disclosure</a>
            </div>
        </div>
    </div>
</footer>
