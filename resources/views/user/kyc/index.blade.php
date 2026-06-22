@extends('layouts.user')

@section('content')
<h1 class="text-3xl font-bold mb-6">KYC Verification</h1>

@if($kyc)
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Current KYC Status</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Document Type</p>
                <p class="font-semibold">{{ $kyc->document_type }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="font-semibold">{{ ucfirst($kyc->status) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Document Number</p>
                <p class="font-semibold">{{ $kyc->document_number ?: '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Submitted At</p>
                <p class="font-semibold">{{ $kyc->submitted_at?->format('Y-m-d H:i') ?: '-' }}</p>
            </div>
        </div>

        @if($kyc->rejection_reason)
            <div class="mt-4 rounded-lg bg-red-50 border border-red-200 p-4">
                <p class="text-sm text-red-500">Rejection Reason</p>
                <p class="text-red-700">{{ $kyc->rejection_reason }}</p>
            </div>
        @endif
    </div>
@endif

<form method="POST" action="{{ route('user.kyc.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-6 max-w-4xl">
    @csrf

    <h2 class="text-xl font-semibold mb-4">Submit / Update KYC</h2>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 font-medium">Document Type</label>
            <select name="document_type" class="w-full border rounded-lg px-4 py-3" required>
                <option value="">Select type</option>
                <option value="national_id">National ID</option>
                <option value="drivers_license">Driver's License</option>
                <option value="international_passport">International Passport</option>
                <option value="voters_card">Voter's Card</option>
            </select>
        </div>

        <div>
            <label class="block mb-2 font-medium">Document Number</label>
            <input type="text" name="document_number" value="{{ old('document_number') }}" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Front Image</label>
            <input type="file" name="front_image" class="w-full border rounded-lg px-4 py-3" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">Back Image</label>
            <input type="file" name="back_image" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Selfie Image</label>
            <input type="file" name="selfie_image" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Address Document</label>
            <input type="file" name="address_document" class="w-full border rounded-lg px-4 py-3">
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="px-6 py-3 rounded-lg bg-slate-900 text-white">Submit KYC</button>
    </div>
</form>
@endsection
