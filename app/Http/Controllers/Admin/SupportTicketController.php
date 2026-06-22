<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(): View
    {
        $tickets = SupportTicket::with(['user', 'assignee'])
            ->latest()
            ->paginate(20);

        return view('admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'messages.user', 'assignee']);

        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        if ($ticket->assigned_to === null) {
            $ticket->update(['assigned_to' => auth()->id()]);
        }

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:open,in_progress,closed'],
        ]);

        $ticket->update([
            'status' => $request->status,
            'assigned_to' => $ticket->assigned_to ?: auth()->id(),
        ]);

        return back()->with('success', 'Ticket status updated successfully.');
    }
}
