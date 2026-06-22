<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateSecurityRequest;
use App\Services\SecurityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class SecurityController extends Controller
{
    public function __construct(
        protected SecurityService $securityService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        $loginLogs = $user->loginLogs()->latest()->take(10)->get();

        return view('user.security.index', compact('user', 'loginLogs'));
    }

    public function update(UpdateSecurityRequest $request): RedirectResponse
    {
        try {
            if ($request->filled('password')) {
                $this->securityService->updatePassword(
                    auth()->user(),
                    (string) $request->current_password,
                    (string) $request->password
                );
            }

            if ($request->filled('withdrawal_pin')) {
                $this->securityService->updateWithdrawalPin(
                    auth()->user(),
                    (string) $request->withdrawal_pin
                );
            }

            return back()->with('success', 'Security settings updated successfully.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
