@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Support Tickets</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">User</th>
                <th class="py-3">Subject</th>
                <th class="py-3">Priority</th>
                <th class="py-3">Status</th>
                <th class="py-3">Assigned To</th>
                <th class="py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="border-b">
                    <td class="py-3">{{ $ticket->user->name }}</td>
                    <td class="py-3">{{ $ticket->subject }}</td>
                    <td class="py-3">{{ ucfirst($ticket->priority) }}</td>
                    <td class="py-3">{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</td>
                    <td class="py-3">{{ $ticket->assignee?->name ?: '-' }}</td>
                    <td class="py-3">
                        <a href="{{ route('admin.support.show', $ticket) }}" class="text-blue-600">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4 text-gray-500">No support tickets found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
