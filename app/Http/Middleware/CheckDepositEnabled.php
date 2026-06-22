<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDepositEnabled
{
    public function __construct(
        protected SettingService $settingService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->settingService->get('deposit_enabled', true)) {
            abort(403, 'Deposits are currently disabled.');
        }

        return $next($request);
    }
}
