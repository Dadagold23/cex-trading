<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high'],
            'message' => ['required', 'string'],
        ];
    }
}
