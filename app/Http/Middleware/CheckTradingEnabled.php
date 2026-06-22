<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTradingEnabled
{
    public function __construct(
        protected SettingService $settingService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->settingService->get('trade_enabled', true)) {
            abort(403, 'Trading is currently disabled.');
        }

        return $next($request);
    }
}
