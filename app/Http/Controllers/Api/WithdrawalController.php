<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\WithdrawalRequest;
use App\Http\Resources\WithdrawalResource;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class WithdrawalController extends Controller
{
    public function __construct(
        protected WithdrawalService $withdrawalService
    ) {
    }

    public function index(): JsonResponse
    {
        $withdrawals = Withdrawal::with('currency')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return ApiResponse::success('Withdrawals loaded successfully.', [
            'withdrawals' => WithdrawalResource::collection($withdrawals),
        ]);
    }

    public function store(WithdrawalRequest $request): JsonResponse
    {
        try {
            $withdrawal = $this->withdrawalService->createWithdrawalRequest(
                auth()->user(),
                $request->validated()
            );

            return ApiResponse::success('Withdrawal request submitted successfully.', [
                'withdrawal' => new WithdrawalResource($withdrawal),
            ], 201);
        } catch (Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 422);
        }
    }
}
