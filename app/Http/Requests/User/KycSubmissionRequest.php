<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class KycSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'max:100'],
            'document_number' => ['nullable', 'string', 'max:255'],
            'front_image' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'back_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'selfie_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:4096'],
            'address_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ];
    }
}
