<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\BankAccountRequest;
use App\Models\BankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BankAccountController extends Controller
{
    public function index(): View
    {
        $bankAccounts = auth()->user()
            ->bankAccounts()
            ->latest()
            ->paginate(15);

        return view('user.bank-accounts.index', compact('bankAccounts'));
    }

    public function create(): View
    {
        return view('user.bank-accounts.create');
    }

    public function store(BankAccountRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            auth()->user()->bankAccounts()->update(['is_default' => false]);
        }

        BankAccount::create($data);

        return redirect()
            ->route('user.bank-accounts.index')
            ->with('success', 'Bank account added successfully.');
    }
}
