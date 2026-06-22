<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BuyTradeRequest;
use App\Http\Requests\User\SellTradeRequest;
use App\Models\Currency;
use App\Models\TradeOrder;
use App\Services\TradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class TradeController extends Controller
{
    public function __construct(
        protected TradeService $tradeService
    ) {
    }

    public function index(): View
    {
        $trades = TradeOrder::with(['currency', 'baseCurrency'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('user.trades.index', compact('trades'));
    }

    public function buy(): View
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $baseCurrencies = Currency::whereIn('code', ['NGN', 'USD'])
            ->where('is_active', true)
            ->get();

        return view('user.trades.buy', compact('currencies', 'baseCurrencies'));
    }

    public function sell(): View
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $baseCurrencies = Currency::whereIn('code', ['NGN', 'USD'])
            ->where('is_active', true)
            ->get();

        return view('user.trades.sell', compact('currencies', 'baseCurrencies'));
    }

    public function storeBuy(BuyTradeRequest $request): RedirectResponse
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

            return redirect()
                ->route('user.trades.index')
                ->with('success', "Buy trade {$trade->reference} submitted successfully.");
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function storeSell(SellTradeRequest $request): RedirectResponse
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

            return redirect()
                ->route('user.trades.index')
                ->with('success', "Sell trade {$trade->reference} submitted successfully.");
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
