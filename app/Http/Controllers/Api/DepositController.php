<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DepositRequest;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use App\Services\DepositService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class DepositController extends Controller
{
    public function __construct(
        protected DepositService $depositService
    ) {
    }

    public function index(): JsonResponse
    {
        $deposits = Deposit::with('currency')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return ApiResponse::success('Deposits loaded successfully.', [
            'deposits' => DepositResource::collection($deposits),
        ]);
    }

    public function store(DepositRequest $request): JsonResponse
    {
        try {
            $deposit = $this->depositService->createDepositRequest(
                auth()->user(),
                $request->validated()
            );

            return ApiResponse::success('Deposit request submitted successfully.', [
                'deposit' => new DepositResource($deposit),
            ], 201);
        } catch (Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 422);
        }
    }
}
