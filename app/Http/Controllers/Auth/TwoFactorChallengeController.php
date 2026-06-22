<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorChallengeController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactorService
    ) {
    }

    public function create(): View|RedirectResponse
    {
        if (!session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $userId = session('2fa:user:id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Authentication session expired.');
        }

        $code = trim($request->code);
        $recoveryCodes = $user->two_factor_recovery_codes ?? [];

        $valid = $this->twoFactorService->verify($user, $code);

        if (!$valid && in_array($code, $recoveryCodes, true)) {
            $valid = true;
            $user->update([
                'two_factor_recovery_codes' => array_values(array_filter(
                    $recoveryCodes,
                    fn ($recoveryCode) => $recoveryCode !== $code
                )),
            ]);
        }

        if (!$valid) {
            return back()->with('error', 'Invalid authentication code.');
        }

        auth()->login($user);
        $request->session()->regenerate();
        session()->forget('2fa:user:id');

        $user->update([
            'last_login_at' => now(),
        ]);

        app(\App\Services\SecurityService::class)->logLogin($user, $request, 'success');

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('user.dashboard'));
    }
}
