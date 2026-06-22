<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        return ApiResponse::success('Profile loaded successfully.', [
            'user' => new UserResource(auth()->user()),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore(auth()->id())],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
            'phone' => ['nullable', 'string', 'max:50', Rule::unique('users', 'phone')->ignore(auth()->id())],
        ]);

        auth()->user()->update($data);

        return ApiResponse::success('Profile updated successfully.', [
            'user' => new UserResource(auth()->user()->fresh()),
        ]);
    }
}
