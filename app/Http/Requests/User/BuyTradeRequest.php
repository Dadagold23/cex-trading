<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class BuyTradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'currency_id' => ['required', 'exists:currencies,id'],
            'base_currency_id' => ['required', 'exists:currencies,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
