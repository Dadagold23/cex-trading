<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class RateController extends Controller
{
    public function index(): JsonResponse
    {
        $rates = Rate::with(['currency', 'baseCurrency'])
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        return ApiResponse::success('Rates loaded successfully.', [
            'rates' => RateResource::collection($rates),
        ]);
    }
}
