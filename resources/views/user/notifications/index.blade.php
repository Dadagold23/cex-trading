@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Notifications</h1>

<div class="mb-6">
    <form method="POST" action="{{ route('user.notifications.read-all') }}">
        @csrf
        <button type="submit" class="px-5 py-3 rounded-lg bg-slate-900 text-white">Mark All as Read</button>
    </form>
</div>

<div class="space-y-4">
    @forelse($notifications as $notification)
        <div class="bg-white rounded-2xl shadow p-5 {{ !$notification->is_read ? 'border-l-4 border-blue-600' : '' }}">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold">{{ $notification->title }}</h3>
                    <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                    <p class="text-sm text-gray-400 mt-2">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
                </div>

                @if(!$notification->is_read)
                    <form method="POST" action="{{ route('user.notifications.read', $notification) }}">
                        @csrf
                        <button type="submit" class="text-sm px-4 py-2 rounded-lg border">Mark Read</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl shadow p-6 text-gray-500">
            No notifications available.
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $notifications->links() }}
</div>
@endsection
