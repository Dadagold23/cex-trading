<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function index(): JsonResponse
    {
        $wallets = auth()->user()
            ->wallets()
            ->with('currency')
            ->orderByDesc('id')
            ->get();

        return ApiResponse::success('Wallets loaded successfully.', [
            'wallets' => WalletResource::collection($wallets),
        ]);
    }
}
