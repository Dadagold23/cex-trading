<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\WithdrawalRequest;
use App\Models\Currency;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class WithdrawalController extends Controller
{
    public function __construct(
        protected WithdrawalService $withdrawalService
    ) {
    }

    public function index(): View
    {
        $withdrawals = Withdrawal::with(['currency', 'reviewer'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('user.withdrawals.index', compact('withdrawals'));
    }

    public function create(): View
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $bankAccounts = auth()->user()->bankAccounts()->latest()->get();

        $methods = [
            'bank_transfer' => 'Bank Transfer',
            'crypto_transfer' => 'Crypto Transfer',
        ];

        return view('user.withdrawals.create', compact('currencies', 'bankAccounts', 'methods'));
    }

    public function store(WithdrawalRequest $request): RedirectResponse
    {
        try {
            $withdrawal = $this->withdrawalService->createWithdrawalRequest(
                auth()->user(),
                $request->validated()
            );

            return redirect()
                ->route('user.withdrawals.index')
                ->with('success', "Withdrawal request {$withdrawal->reference} submitted successfully.");
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Withdrawal $withdrawal): View
    {
        $this->authorize('view', $withdrawal);

        $withdrawal->load(['currency', 'reviewer']);

        return view('user.withdrawals.show', compact('withdrawal'));
    }
}
