<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycRecord;
use App\Services\KycService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class KycController extends Controller
{
    public function __construct(
        protected KycService $kycService
    ) {
    }

    public function index(): View
    {
        $kycRecords = KycRecord::with(['user', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('admin.kyc.index', compact('kycRecords'));
    }

    public function show(KycRecord $kyc): View
    {
        $kyc->load(['user', 'reviewer']);

        return view('admin.kyc.show', compact('kyc'));
    }

    public function approve(KycRecord $kyc): RedirectResponse
    {
        try {
            $this->kycService->approve($kyc, auth()->user());

            return redirect()
                ->route('admin.kyc.show', $kyc)
                ->with('success', 'KYC approved successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, KycRecord $kyc): RedirectResponse
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        try {
            $this->kycService->reject($kyc, auth()->user(), $request->reason);

            return redirect()
                ->route('admin.kyc.show', $kyc)
                ->with('success', 'KYC rejected successfully.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
