@extends('layouts.user')

@section('content')
<div class="page-shell">
    <div class="page-header" data-aos="fade-up">
        <div>
            <h1 class="page-title">Two-Factor Authentication</h1>
            <p class="page-description">Protect your account with authenticator-based login security.</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 content-card" data-aos="fade-right">
            <div class="mb-6">
                <p class="text-sm text-gray-500">Current Status</p>
                <p class="font-semibold">
                    {{ $user->hasTwoFactorEnabled() ? 'Enabled' : 'Not Enabled' }}
                </p>
            </div>

            @if(!$user->hasTwoFactorEnabled())
                @if(!$pendingSecret)
                    <form method="POST" action="{{ route('user.two-factor.start') }}">
                        @csrf
                        <button type="submit" class="dashboard-primary">Start Setup</button>
                    </form>
                @else
                    <div class="space-y-6">
                        <div>
                            <p class="font-medium mb-3">Step 1: Scan this QR code with your authenticator app</p>
                            <div class="bg-white border rounded-2xl p-4 inline-block">
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrCodeUrl) }}"
                                    alt="2FA QR Code"
                                >
                            </div>
                        </div>

                        <div>
                            <p class="font-medium mb-2">Manual secret</p>
                            <div class="rounded-2xl border bg-gray-50 p-4 font-mono break-all">
                                {{ $pendingSecret }}
                            </div>
                        </div>

                        <form method="POST" action="{{ route('user.two-factor.confirm') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="form-label">Enter 6-digit code</label>
                                <input type="text" name="code" class="form-input" maxlength="6" required>
                            </div>
                            <button type="submit" class="dashboard-primary">Confirm and Enable</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="space-y-6">
                    <div>
                        <p class="font-medium mb-3">Recovery Codes</p>
                        <div class="grid md:grid-cols-2 gap-3">
                            @foreach(($user->two_factor_recovery_codes ?? []) as $code)
                                <div class="rounded-2xl border bg-gray-50 p-4 font-mono">{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.two-factor.recovery-codes') }}">
                        @csrf
                        <button type="submit" class="dashboard-action">Regenerate Recovery Codes</button>
                    </form>

                    <form method="POST" action="{{ route('user.two-factor.disable') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">Enter current 2FA code to disable</label>
                            <input type="text" name="code" class="form-input" maxlength="6" required>
                        </div>
                        <button type="submit" class="dashboard-danger">Disable 2FA</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="content-card" data-aos="fade-left">
            <h2 class="text-xl font-semibold mb-4">Why Enable 2FA?</h2>
            <ul class="space-y-3 text-gray-600">
                <li>Protects your account even if your password is exposed.</li>
                <li>Strongly recommended for all users.</li>
                <li>Mandatory for admin and super admin accounts.</li>
                <li>Recovery codes help you regain access if you lose your device.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
