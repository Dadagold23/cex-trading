<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {
    }

    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('user.notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        $this->authorize('view', $notification);

        $this->notificationService->markAsRead($notification);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $this->notificationService->markAllAsReadForUser(auth()->user());

        return back()->with('success', 'All notifications marked as read.');
    }
}
