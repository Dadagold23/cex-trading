<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();

        return ApiResponse::success('Notifications loaded successfully.', [
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }
}
