<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveTradeRequest;
use App\Http\Requests\Admin\RejectTradeRequest;
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
        $trades = TradeOrder::with(['user', 'currency', 'baseCurrency'])
            ->latest()
            ->paginate(20);

        return view('admin.trades.index', compact('trades'));
    }

    public function show(TradeOrder $trade): View
    {
        $trade->load(['user', 'currency', 'baseCurrency']);

        return view('admin.trades.show', compact('trade'));
    }

    public function approve(ApproveTradeRequest $request, TradeOrder $trade): RedirectResponse
    {
        try {
            $this->tradeService->approveTrade($trade, auth()->user(), $request);

            return redirect()
                ->route('admin.trades.show', $trade)
                ->with('success', 'Trade approved successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(RejectTradeRequest $request, TradeOrder $trade): RedirectResponse
    {
        try {
            $this->tradeService->rejectTrade($trade, auth()->user(), $request->reason, $request);

            return redirect()
                ->route('admin.trades.show', $trade)
                ->with('success', 'Trade rejected successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
