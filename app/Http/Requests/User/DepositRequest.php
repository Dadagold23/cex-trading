<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositRequest extends FormRequest
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
            'method' => ['required', Rule::in(['bank_transfer', 'crypto_transfer', 'gateway'])],
            'sender_name' => ['nullable', 'string', 'max:255'],
            'sender_account' => ['nullable', 'string', 'max:255'],
            'gateway_reference' => ['nullable', 'string', 'max:255'],
            'transaction_hash' => ['nullable', 'string', 'max:255', 'required_if:method,crypto_transfer'],
            'from_wallet_address' => ['nullable', 'string', 'max:255'],
            'to_wallet_address' => ['nullable', 'string', 'max:255'],
            'network' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'proof_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ];
    }
}
