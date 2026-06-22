<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Deposit;
use App\Services\DepositService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class DepositController extends Controller
{
    public function __construct(
        protected DepositService $depositService
    ) {
    }

    public function index(Request $request): View
    {
        $deposits = Deposit::with(['user', 'currency', 'reviewer'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('reference', 'like', "%{$q}%")
                        ->orWhere('transaction_hash', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($userQuery) use ($q) {
                            $userQuery->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('method'), fn ($query) => $query->where('method', $request->method))
            ->when($request->filled('currency_id'), fn ($query) => $query->where('currency_id', $request->currency_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $currencies = Currency::where('is_active', true)->orderBy('code')->get();

        return view('admin.deposits.index', compact('deposits', 'currencies'));
    }

    public function export(Request $request): Response
    {
        $rows = Deposit::with(['user', 'currency'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('reference', 'like', "%{$q}%")
                        ->orWhere('transaction_hash', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($userQuery) use ($q) {
                            $userQuery->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('method'), fn ($query) => $query->where('method', $request->method))
            ->when($request->filled('currency_id'), fn ($query) => $query->where('currency_id', $request->currency_id))
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="deposits.csv"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Reference', 'User', 'Email', 'Currency', 'Amount', 'Method', 'Status', 'Transaction Hash', 'Created At']);

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->reference,
                    $row->user?->name,
                    $row->user?->email,
                    $row->currency?->code,
                    $row->amount,
                    $row->method,
                    $row->status,
                    $row->transaction_hash,
                    $row->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Deposit $deposit): View
    {
        $deposit->load(['user', 'currency', 'reviewer', 'statusLogs.creator']);

        return view('admin.deposits.show', compact('deposit'));
    }

    public function approve(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        try {
            $this->depositService->approveDeposit($deposit, auth()->user(), $request->input('note'));

            return redirect()
                ->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit approved and wallet credited successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Deposit $deposit): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        try {
            $this->depositService->rejectDeposit($deposit, auth()->user(), $request->input('reason'));

            return redirect()
                ->route('admin.deposits.show', $deposit)
                ->with('success', 'Deposit rejected successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
