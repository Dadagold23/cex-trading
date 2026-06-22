<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();

        $pendingSecret = session('two_factor_pending_secret');
        $qrCodeUrl = null;

        if ($pendingSecret) {
            $qrCodeUrl = $this->twoFactorService->getQrCodeUrl($user, $pendingSecret);
        }

        return view('user.security.two-factor', compact('user', 'pendingSecret', 'qrCodeUrl'));
    }

    public function start(): RedirectResponse
    {
        $secret = $this->twoFactorService->generateSecret();

        session(['two_factor_pending_secret' => $secret]);

        return back()->with('success', 'Scan the QR code and confirm with your authenticator code.');
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $secret = session('two_factor_pending_secret');

        if (!$secret) {
            return back()->with('error', 'No pending 2FA setup found.');
        }

        $user = auth()->user();
        $qrOwnerLikeUser = clone $user;
        $qrOwnerLikeUser->two_factor_secret = encrypt($secret);

        if (!$this->twoFactorService->verify($qrOwnerLikeUser, $request->code)) {
            return back()->with('error', 'Invalid authentication code.');
        }

        $this->twoFactorService->enable($user, $secret);

        session()->forget('two_factor_pending_secret');

        return back()->with('success', 'Two-factor authentication enabled successfully.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = auth()->user();

        if (!$this->twoFactorService->verify($user, $request->code)) {
            return back()->with('error', 'Invalid authentication code.');
        }

        $this->twoFactorService->disable($user);

        return back()->with('success', 'Two-factor authentication disabled.');
    }

    public function regenerateRecoveryCodes(): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'two_factor_recovery_codes' => $this->twoFactorService->generateRecoveryCodes(),
        ]);

        return back()->with('success', 'Recovery codes regenerated successfully.');
    }
}
