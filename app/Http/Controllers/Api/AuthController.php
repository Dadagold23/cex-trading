<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SecurityService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected SecurityService $securityService
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            $this->securityService->logLogin(null, request(), 'failed');

            return ApiResponse::error('Invalid credentials.', null, 422);
        }

        if (! $user->hasRole('user')) {
            return ApiResponse::error('This endpoint is for user accounts only.', null, 403);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        $this->securityService->logLogin($user, request(), 'success');

        return ApiResponse::success('Login successful.', [
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()?->currentAccessToken()?->delete();

        return ApiResponse::success('Logged out successfully.');
    }
}
