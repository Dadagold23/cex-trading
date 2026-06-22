<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'bank_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'swift_code' => ['nullable', 'string', 'max:100'],
            'currency_code' => ['required', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ];
    }
}
