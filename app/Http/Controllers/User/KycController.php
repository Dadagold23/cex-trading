<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\KycSubmissionRequest;
use App\Services\KycService;
use Illuminate\Http\RedirectResponse;
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
        $kyc = auth()->user()->kycRecords()->latest()->first();

        return view('user.kyc.index', compact('kyc'));
    }

    public function store(KycSubmissionRequest $request): RedirectResponse
    {
        try {
            $this->kycService->submit(auth()->user(), [
                ...$request->validated(),
                'front_image' => $request->file('front_image'),
                'back_image' => $request->file('back_image'),
                'selfie_image' => $request->file('selfie_image'),
                'address_document' => $request->file('address_document'),
            ]);

            return redirect()
                ->route('user.kyc.index')
                ->with('success', 'KYC submitted successfully.');
        } catch (Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
