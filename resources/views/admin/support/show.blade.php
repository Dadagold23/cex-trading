@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Support Ticket Review</h1>

<div class="bg-white rounded-2xl shadow p-6 mb-6">
    <h2 class="text-xl font-semibold">{{ $ticket->subject }}</h2>
    <p class="text-sm text-gray-500 mt-1">
        User: {{ $ticket->user->name }} |
        Priority: {{ ucfirst($ticket->priority) }} |
        Status: {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
    </p>
</div>

<div class="space-y-4 mb-6">
    @foreach($ticket->messages as $message)
        <div class="bg-white rounded-2xl shadow p-5">
            <p class="font-semibold">{{ $message->user->name }}</p>
            <p class="text-gray-600 mt-2">{{ $message->message }}</p>
            <p class="text-sm text-gray-400 mt-2">{{ $message->created_at->format('Y-m-d H:i') }}</p>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('admin.support.reply', $ticket) }}" class="bg-white rounded-2xl shadow p-6">
        @csrf
        <label class="block mb-2 font-medium">Reply</label>
        <textarea name="message" rows="4" class="w-full border rounded-lg px-4 py-3 mb-4" required></textarea>
        <button type="submit" class="px-6 py-3 rounded-lg bg-black text-white">Send Reply</button>
    </form>

    <form method="POST" action="{{ route('admin.support.status', $ticket) }}" class="bg-white rounded-2xl shadow p-6">
        @csrf
        <label class="block mb-2 font-medium">Update Status</label>
        <select name="status" class="w-full border rounded-lg px-4 py-3 mb-4" required>
            <option value="open" @selected($ticket->status === 'open')>Open</option>
            <option value="in_progress" @selected($ticket->status === 'in_progress')>In Progress</option>
            <option value="closed" @selected($ticket->status === 'closed')>Closed</option>
        </select>
        <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Update Status</button>
    </form>
</div>
@endsection
