@extends('layouts.user')

@section('content')
<div class="mb-6" data-aos="fade-up">
    <h1 class="text-3xl font-bold">{{ $ticket->subject }}</h1>
    <p class="text-gray-500 mt-1">
        Priority: {{ ucfirst($ticket->priority) }} |
        Status: {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
    </p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 dashboard-card" data-aos="fade-right">
        <h2 class="text-xl font-semibold mb-4">Conversation</h2>

        <div class="space-y-4">
            @forelse($ticket->messages as $message)
                <div class="border rounded-2xl p-4 animated-card">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold">{{ $message->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $message->user->email }}</p>
                        </div>
                        <p class="text-sm text-gray-400">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="mt-3 text-gray-700 leading-7">
                        {{ $message->message }}
                    </div>

                    @if($message->attachment)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="text-blue-600 underline" data-click-sound="true">
                                View Attachment
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No messages yet.</p>
            @endforelse
        </div>
    </div>

    <div class="space-y-6" data-aos="fade-left">
        <div class="dashboard-card">
            <h2 class="text-xl font-semibold mb-4">Ticket Details</h2>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500">Subject</p>
                    <p class="font-semibold">{{ $ticket->subject }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Priority</p>
                    <p class="font-semibold">{{ ucfirst($ticket->priority) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold">{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Assigned To</p>
                    <p class="font-semibold">{{ $ticket->assignee?->name ?: '-' }}</p>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <h2 class="text-xl font-semibold mb-4">Reply</h2>

            <form method="POST" action="{{ route('user.support.reply', $ticket) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-2 font-medium">Message</label>
                    <textarea name="message" rows="5" class="w-full border rounded-xl px-4 py-3" required>{{ old('message') }}</textarea>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Attachment</label>
                    <input type="file" name="attachment" class="w-full border rounded-xl px-4 py-3">
                </div>

                <button type="submit" class="dashboard-primary">Send Reply</button>
            </form>
        </div>
    </div>
</div>
@endsection
