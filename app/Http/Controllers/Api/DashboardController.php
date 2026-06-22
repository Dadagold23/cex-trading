<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Models\Deposit;
use App\Models\TradeOrder;
use App\Models\Withdrawal;
use App\Services\KycService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected KycService $kycService
    ) {
    }

    public function index(): JsonResponse
    {
        $user = auth()->user()->load(['wallets.currency']);

        return ApiResponse::success('Dashboard loaded successfully.', [
            'user' => new UserResource($user),
            'wallets' => WalletResource::collection($user->wallets),
            'stats' => [
                'wallet_count' => $user->wallets->count(),
                'trades_count' => TradeOrder::where('user_id', $user->id)->count(),
                'deposits_count' => Deposit::where('user_id', $user->id)->count(),
                'withdrawals_count' => Withdrawal::where('user_id', $user->id)->count(),
                'unread_notifications' => $user->notifications()->where('is_read', false)->count(),
                'kyc_approved' => $this->kycService->isApproved($user),
            ],
        ]);
    }
}
