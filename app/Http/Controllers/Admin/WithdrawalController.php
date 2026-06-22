<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $withdrawals = Withdrawal::with(['user', 'currency', 'reviewer'])
            ->latest()
            ->paginate(20);

            ->when($request->filled('q'), function ($query) use ($request) {
    $q = $request->q;
    $query->where(function ($sub) use ($q) {
        $sub->where('reference', 'like', "%{$q}%")
            ->orWhereHas('user', function ($userQuery) use ($q) {
                $userQuery->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
    });
})
->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
->when($request->filled('currency_id'), fn ($query) => $query->where('currency_id', $request->currency_id))


        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function show(Withdrawal $withdrawal): View
    {
        $withdrawal->load(['user', 'currency', 'reviewer']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal): RedirectResponse
    {
        try {
            $this->withdrawalService->approveWithdrawal($withdrawal, auth()->user());

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Withdrawal approved successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        try {
            $this->withdrawalService->rejectWithdrawal(
                $withdrawal,
                auth()->user(),
                $request->input('reason')
            );

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Withdrawal rejected and funds released back to wallet.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
