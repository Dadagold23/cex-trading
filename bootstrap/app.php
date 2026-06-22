<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
})
->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureAdmin::class,
        'user' => \App\Http\Middleware\EnsureUser::class,
    ]);

    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureAdmin::class,
        'user' => \App\Http\Middleware\EnsureUser::class,
        'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        'trade.enabled' => \App\Http\Middleware\CheckTradingEnabled::class,
        'deposit.enabled' => \App\Http\Middleware\CheckDepositEnabled::class,
        'withdrawal.enabled' => \App\Http\Middleware\CheckWithdrawalEnabled::class,
        'crypto.webhook' => \App\Http\Middleware\VerifyCryptoWebhookSignature::class,
        'admin.2fa' => \App\Http\Middleware\EnsureTwoFactorEnabledForAdmins::class,
    ]);
})->create();
