<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Services\DepositService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CryptoDepositWebhookController extends Controller
{
    public function __construct(
        protected DepositService $depositService
    ) {
    }

    public function handle(Request $request): JsonResponse
    {
        // Replace these keys with your real provider payload mapping later
        $transactionHash = $request->input('transaction_hash');
        $status = $request->input('status');

        if (!$transactionHash || !$status) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid webhook payload.',
            ], 422);
        }

        $deposit = Deposit::where('transaction_hash', $transactionHash)->first();

        if (!$deposit) {
            return response()->json([
                'success' => false,
                'message' => 'Deposit not found.',
            ], 404);
        }

        if (strtolower($status) === 'confirmed') {
            $this->depositService->confirmViaWebhook(
                $deposit,
                'Deposit confirmed via webhook callback.'
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed successfully.',
        ]);

        if (strtolower($status) === 'confirmed') {
            $this->depositService->confirmViaWebhook(
                $deposit,
                'Deposit confirmed automatically via signed webhook callback.'
                );
        }
    }
}
