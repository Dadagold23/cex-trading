<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BuyTradeRequest;
use App\Http\Requests\User\SellTradeRequest;
use App\Http\Resources\TradeResource;
use App\Models\Currency;
use App\Models\TradeOrder;
use App\Services\TradeService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

class TradeController extends Controller
{
    public function __construct(
        protected TradeService $tradeService
    ) {
    }

    public function index(): JsonResponse
    {
        $trades = TradeOrder::with(['currency', 'baseCurrency'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return ApiResponse::success('Trades loaded successfully.', [
            'trades' => TradeResource::collection($trades),
        ]);
    }

    public function buy(BuyTradeRequest $request): JsonResponse
    {
        try {
            $currency = Currency::findOrFail($request->integer('currency_id'));
            $baseCurrency = Currency::findOrFail($request->integer('base_currency_id'));

            $trade = $this->tradeService->createBuyOrder(
                auth()->user(),
                $currency,
                $baseCurrency,
                (float) $request->amount
            );

            return ApiResponse::success('Buy trade submitted successfully.', [
                'trade' => new TradeResource($trade),
            ], 201);
        } catch (Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 422);
        }
    }

    public function sell(SellTradeRequest $request): JsonResponse
    {
        try {
            $currency = Currency::findOrFail($request->integer('currency_id'));
            $baseCurrency = Currency::findOrFail($request->integer('base_currency_id'));

            $trade = $this->tradeService->createSellOrder(
                auth()->user(),
                $currency,
                $baseCurrency,
                (float) $request->amount
            );

            return ApiResponse::success('Sell trade submitted successfully.', [
                'trade' => new TradeResource($trade),
            ], 201);
        } catch (Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 422);
        }
    }
}
