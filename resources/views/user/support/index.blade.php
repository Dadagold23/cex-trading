@extends('layouts.user')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6" data-aos="fade-up">
    <div>
        <h1 class="text-3xl font-bold">Support Tickets</h1>
        <p class="text-gray-500 mt-1">Get help with deposits, withdrawals, trades, and account issues.</p>
    </div>

    <a href="{{ route('user.support.create') }}" class="dashboard-primary" data-click-sound="true">Open Ticket</a>
</div>

<div class="dashboard-card mb-6" data-aos="fade-up" data-aos-delay="100">
    <form method="GET" action="{{ url()->current() }}" class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block mb-2 text-sm font-medium">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input" placeholder="Reference, subject, status">
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium">Status</label>
            <input type="text" name="status" value="{{ request('status') }}" class="form-input" placeholder="pending / approved / rejected">
        </div>

        <div class="flex items-end gap-3">
            <button type="submit" class="dashboard-primary">Filter</button>
            <a href="{{ url()->current() }}" class="dashboard-action" data-click-sound="true">Reset</a>
        </div>
    </form>
</div>

<div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">Subject</th>
                    <th class="py-3">Priority</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Assigned To</th>
                    <th class="py-3">Date</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3">{{ $ticket->subject }}</td>
                        <td class="py-3">{{ ucfirst($ticket->priority) }}</td>
                        <td class="py-3">{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</td>
                        <td class="py-3">{{ $ticket->assignee?->name ?: '-' }}</td>
                        <td class="py-3">{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-3">
                            <a href="{{ route('user.support.show', $ticket) }}" class="text-slate-700 hover:underline" data-click-sound="true">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-6 text-gray-500">No support tickets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
</div>
@endsection
