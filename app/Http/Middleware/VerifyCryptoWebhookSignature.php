<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCryptoWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $providedSignature = (string) $request->header('X-Webhook-Signature', '');
        $secret = (string) config('services.crypto_webhook.secret', env('CRYPTO_WEBHOOK_SECRET'));

        if ($providedSignature === '' || $secret === '') {
            abort(403, 'Missing webhook signature.');
        }

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expectedSignature, $providedSignature)) {
            abort(403, 'Invalid webhook signature.');
        }

        return $next($request);
    }
}
