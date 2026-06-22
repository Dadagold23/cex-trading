<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(ContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::create($request->validated());

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
