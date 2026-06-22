<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DepositRequest;
use App\Models\Currency;
use App\Models\Deposit;
use App\Services\DepositService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class DepositController extends Controller
{
    public function __construct(
        protected DepositService $depositService
    ) {
    }

    public function index(): View
    {
        $deposits = Deposit::with('currency')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('user.deposits.index', compact('deposits'));
    }

    public function create(): View
    {
        $currencies = Currency::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $methods = [
            'bank_transfer' => 'Bank Transfer',
            'crypto_transfer' => 'Crypto Transfer',
            'gateway' => 'Payment Gateway',
        ];

        return view('user.deposits.create', compact('currencies', 'methods'));
    }

    public function store(DepositRequest $request): RedirectResponse
    {
        try {
            $deposit = $this->depositService->createDepositRequest(
                auth()->user(),
                $request->validated() + ['proof_file' => $request->file('proof_file')]
            );

            return redirect()
                ->route('user.deposits.index')
                ->with('success', "Deposit request {$deposit->reference} submitted successfully.");
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Deposit $deposit): View
    {
        $this->authorize('view', $deposit);
        
        $deposit->load(['currency', 'reviewer', 'statusLogs.creator']);

        return view('user.deposits.show', compact('deposit'));
    }


    
}
