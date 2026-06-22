@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Audit Logs</h1>

<div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="py-3">Date</th>
                <th class="py-3">Actor</th>
                <th class="py-3">Action</th>
                <th class="py-3">Module</th>
                <th class="py-3">Target ID</th>
                <th class="py-3">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr class="border-b">
                    <td class="py-3">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    <td class="py-3">{{ $log->actor?->name ?: '-' }}</td>
                    <td class="py-3">{{ $log->action }}</td>
                    <td class="py-3">{{ ucfirst($log->module) }}</td>
                    <td class="py-3">{{ $log->target_id ?: '-' }}</td>
                    <td class="py-3">{{ $log->ip_address ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4 text-gray-500">No audit logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
