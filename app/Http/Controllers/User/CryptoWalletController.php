<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\CryptoWalletAddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CryptoWalletController extends Controller
{
    public function __construct(
        protected CryptoWalletAddressService $cryptoWalletAddressService
    ) {
    }

    public function index(): View
    {
        $cryptoWallets = auth()->user()
            ->cryptoWallets()
            ->with('currency')
            ->latest()
            ->get();

        return view('user.crypto-wallets.index', compact('cryptoWallets'));
    }

    public function show(Currency $currency): View
    {
        $wallet = auth()->user()
            ->cryptoWallets()
            ->with('currency')
            ->where('currency_id', $currency->id)
            ->firstOrFail();

        return view('user.crypto-wallets.show', compact('wallet'));
    }

    public function deposit(Currency $currency): View
    {
        $wallet = auth()->user()
            ->cryptoWallets()
            ->with('currency')
            ->where('currency_id', $currency->id)
            ->firstOrFail();

        return view('user.crypto-wallets.deposit', compact('wallet'));
    }

    public function regenerate(Currency $currency): RedirectResponse
    {
        if ($currency->type !== 'crypto') {
            return back()->with('error', 'Only crypto wallet addresses can be regenerated.');
        }

        $this->cryptoWalletAddressService->regenerateForUser(auth()->user(), $currency);

        return back()->with('success', "{$currency->code} wallet address regenerated successfully.");
    }
}
