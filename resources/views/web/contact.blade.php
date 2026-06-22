@extends('layouts.guest')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-16">
    <h1 class="text-3xl font-bold mb-6">Contact Us</h1>

    @include('partials.alerts')

    <form method="POST" action="{{ route('contact.store') }}" class="bg-white rounded-2xl shadow p-6 max-w-3xl">
        @csrf

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 font-medium">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-lg px-4 py-3" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-3" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 font-medium">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full border rounded-lg px-4 py-3" required>
            </div>
        </div>

        <div class="mt-4">
            <label class="block mb-2 font-medium">Message</label>
            <textarea name="message" rows="6" class="w-full border rounded-lg px-4 py-3" required>{{ old('message') }}</textarea>
        </div>

        <div class="mt-6">
            <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Send Message</button>
        </div>
    </form>
</div>
@endsection
