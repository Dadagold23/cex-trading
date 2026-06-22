@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">Open Support Ticket</h1>

<form method="POST" action="{{ route('user.support.store') }}" class="bg-white rounded-2xl shadow p-6 max-w-3xl">
    @csrf

    <div class="mb-4">
        <label class="block mb-2 font-medium">Subject</label>
        <input type="text" name="subject" value="{{ old('subject') }}" class="w-full border rounded-lg px-4 py-3" required>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Priority</label>
        <select name="priority" class="w-full border rounded-lg px-4 py-3" required>
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="block mb-2 font-medium">Message</label>
        <textarea name="message" rows="6" class="w-full border rounded-lg px-4 py-3" required>{{ old('message') }}</textarea>
    </div>

    <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Submit Ticket</button>
</form>
@endsection
