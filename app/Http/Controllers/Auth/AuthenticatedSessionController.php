<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = auth()->user();
        Auth::logout();

        if ($user->hasTwoFactorEnabled()) {
            session(['2fa:user:id' => $user->id]);

            return redirect()->route('two-factor.challenge');
        }

        auth()->login($user);
        $request->session()->regenerate();
        $request->session()->put('deposit_session_token', \Illuminate\Support\Str::uuid()->toString());

        $user->update([
            'last_login_at' => now(),
        ]);

        app(\App\Services\SecurityService::class)->logLogin($user, $request, 'success');

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('user.dashboard'));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}