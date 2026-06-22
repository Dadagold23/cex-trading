<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWithdrawalEnabled
{
    public function __construct(
        protected SettingService $settingService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->settingService->get('withdrawal_enabled', true)) {
            abort(403, 'Withdrawals are currently disabled.');
        }

        return $next($request);
    }
}
