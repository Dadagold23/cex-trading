<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorEnabledForAdmins
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->hasAnyRole(['admin', 'super_admin']) && !$user->hasTwoFactorEnabled()) {
            if (!$request->routeIs('user.two-factor.index', 'user.two-factor.start', 'user.two-factor.confirm')) {
                return redirect()->route('user.two-factor.index')
                    ->with('error', 'Two-factor authentication is required for admin accounts.');
            }
        }

        return $next($request);
    }
}
