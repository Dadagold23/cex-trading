<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(): View
    {
        $wallets = auth()->user()
            ->wallets()
            ->with('currency')
            ->orderByDesc('id')
            ->paginate(20);

        return view('user.wallets.index', compact('wallets'));
    }
}
