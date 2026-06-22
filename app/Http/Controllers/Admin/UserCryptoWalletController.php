<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCryptoWallet;
use App\Services\CryptoWalletAddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserCryptoWalletController extends Controller
{
    public function __construct(
        protected CryptoWalletAddressService $cryptoWalletAddressService
    ) {
    }

    public function index(): View
    {
        $wallets = UserCryptoWallet::with(['user', 'currency'])
            ->latest()
            ->paginate(30);

        return view('admin.crypto-wallets.index', compact('wallets'));
    }

    public function rotate(UserCryptoWallet $cryptoWallet): RedirectResponse
    {
        $this->cryptoWalletAddressService->regenerateForUser(
            $cryptoWallet->user,
            $cryptoWallet->currency
        );

        return back()->with('success', "{$cryptoWallet->currency->code} wallet rotated successfully for {$cryptoWallet->user->name}.");
    }
}
