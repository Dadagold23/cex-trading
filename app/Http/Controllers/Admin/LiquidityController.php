<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\LiquidityAccount;
use App\Services\AuditService;
use App\Services\LiquidityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class LiquidityController extends Controller
{
    public function __construct(
        protected LiquidityService $liquidityService,
        protected AuditService $auditService
    ) {
    }

    public function index(): View
    {
        $accounts = LiquidityAccount::with('currency')
            ->latest()
            ->paginate(20);

        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.liquidity.index', compact('accounts', 'currencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'currency_id' => ['required', 'exists:currencies,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'action_type' => ['required', 'in:add,deduct'],
            'note' => ['nullable', 'string'],
        ]);

        try {
            $currency = Currency::findOrFail($data['currency_id']);
            $reference = 'LIQ-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));

            if ($data['action_type'] === 'add') {
                $this->liquidityService->add(
                    $currency,
                    (float) $data['amount'],
                    $reference,
                    'manual_add',
                    $data['note'] ?? null,
                    auth()->user()
                );
            } else {
                $this->liquidityService->deduct(
                    $currency,
                    (float) $data['amount'],
                    $reference,
                    'manual_deduct',
                    $data['note'] ?? null,
                    auth()->user()
                );
            }

            $this->auditService->log(
                auth()->user(),
                $data['action_type'] . '_liquidity',
                'liquidity',
                $currency->id,
                null,
                [
                    'currency' => $currency->code,
                    'amount' => (float) $data['amount'],
                    'reference' => $reference,
                ],
                $request
            );

            return back()->with('success', 'Liquidity updated successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
