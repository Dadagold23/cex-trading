@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">KYC Records</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">User</th>
                <th class="py-3">Document Type</th>
                <th class="py-3">Document Number</th>
                <th class="py-3">Status</th>
                <th class="py-3">Submitted</th>
                <th class="py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kycRecords as $record)
                <tr class="border-b">
                    <td class="py-3">{{ $record->user->name }}</td>
                    <td class="py-3">{{ $record->document_type }}</td>
                    <td class="py-3">{{ $record->document_number ?: '-' }}</td>
                    <td class="py-3">{{ ucfirst($record->status) }}</td>
                    <td class="py-3">{{ $record->submitted_at?->format('Y-m-d H:i') ?: '-' }}</td>
                    <td class="py-3">
                        <a href="{{ route('admin.kyc.show', $record) }}" class="text-blue-600">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4 text-gray-500">No KYC records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $kycRecords->links() }}
    </div>
</div>
@endsection
