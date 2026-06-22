<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SupportTicketRequest;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(): View
    {
        $tickets = auth()->user()
            ->supportTickets()
            ->latest()
            ->paginate(15);

        return view('user.support.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('user.support.create');
    }

    public function store(SupportTicketRequest $request): RedirectResponse
    {
        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()
            ->route('user.support.index')
            ->with('success', 'Support ticket created successfully.');
    }

    public function show(SupportTicket $ticket): View
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $ticket->load(['messages.user', 'assignee']);

        return view('user.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $request->validate([
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 
            'mimes:jpg,jpeg,png,pdf,doc,docx','max:4096'], // 4MB max
        ]);
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('support', 'public'); 
                }
                $ticket->messages()->create([
                    'user_id' => auth()->id(),
                    'message' => $request->message,
                    'attachment' => $attachmentPath,
                ]);
                if ($request->file('attachment')->getSize() > 4 * 1024 * 1024) {
                    return back()->withErrors(['attachment' => 'File size exceeds the maximum allowed size of 4MB.']);
                }

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment' => $request->file('attachment') ? $request->file('attachment')->store('support-attachments') : null,
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}
