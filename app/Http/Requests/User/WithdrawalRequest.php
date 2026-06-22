<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'currency_id' => ['required', 'exists:currencies,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'string', 'max:50'],
            'destination_type' => ['required', 'string', 'max:50'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'wallet_address' => ['nullable', 'string', 'max:255'],
            'wallet_network' => ['nullable', 'string', 'max:100'],
            'withdrawal_pin' => ['required', 'digits:4'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
