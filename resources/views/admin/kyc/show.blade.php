@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">KYC Review</h1>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div><p class="text-sm text-gray-500">User</p><p class="font-semibold">{{ $kyc->user->name }}</p></div>
            <div><p class="text-sm text-gray-500">Email</p><p class="font-semibold">{{ $kyc->user->email }}</p></div>
            <div><p class="text-sm text-gray-500">Document Type</p><p class="font-semibold">{{ $kyc->document_type }}</p></div>
            <div><p class="text-sm text-gray-500">Document Number</p><p class="font-semibold">{{ $kyc->document_number ?: '-' }}</p></div>
            <div><p class="text-sm text-gray-500">Status</p><p class="font-semibold">{{ ucfirst($kyc->status) }}</p></div>
            <div><p class="text-sm text-gray-500">Submitted At</p><p class="font-semibold">{{ $kyc->submitted_at?->format('Y-m-d H:i') ?: '-' }}</p></div>
        </div>

        <div class="mt-6 grid md:grid-cols-2 gap-4">
            @if($kyc->front_image)
                <a href="{{ asset('storage/' . $kyc->front_image) }}" target="_blank" class="text-blue-600 underline">View Front Image</a>
            @endif
            @if($kyc->back_image)
                <a href="{{ asset('storage/' . $kyc->back_image) }}" target="_blank" class="text-blue-600 underline">View Back Image</a>
            @endif
            @if($kyc->selfie_image)
                <a href="{{ asset('storage/' . $kyc->selfie_image) }}" target="_blank" class="text-blue-600 underline">View Selfie Image</a>
            @endif
            @if($kyc->address_document)
                <a href="{{ asset('storage/' . $kyc->address_document) }}" target="_blank" class="text-blue-600 underline">View Address Document</a>
            @endif
        </div>

        @if($kyc->rejection_reason)
            <div class="mt-6 rounded-lg bg-red-50 border border-red-200 p-4">
                <p class="text-sm text-red-500">Rejection Reason</p>
                <p class="text-red-700">{{ $kyc->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Action Panel</h2>

        @if($kyc->status !== 'approved')
            <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}" class="mb-6">
                @csrf
                <button type="submit" class="w-full px-6 py-3 rounded-lg bg-green-600 text-white">Approve KYC</button>
            </form>
        @endif

        <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}">
            @csrf
            <label class="block mb-2 font-medium">Rejection Reason</label>
            <textarea name="reason" rows="3" class="w-full border rounded-lg px-4 py-3 mb-4" required></textarea>
            <button type="submit" class="w-full px-6 py-3 rounded-lg bg-red-600 text-white">Reject KYC</button>
        </form>
    </div>
</div>
@endsection
