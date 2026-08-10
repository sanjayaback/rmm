@extends('layouts.app')

@section('title', 'Admin Audit Logs CRM')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">📜 Administrative Audit Logs</h1>
            <p class="text-sm text-gray-500 mt-1">Immutable trail of all administrative actions, moderation steps, and setting changes.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-200 transition">
            ← Back to Admin
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase font-bold border-b">
                <tr>
                    <th class="px-6 py-4">ID / Timestamp</th>
                    <th class="px-6 py-4">Admin User</th>
                    <th class="px-6 py-4">Action</th>
                    <th class="px-6 py-4">Target</th>
                    <th class="px-6 py-4">Details</th>
                    <th class="px-6 py-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-gray-900">#{{ $log->id }}</span>
                            <div class="text-xs text-gray-400">{{ $log->created_at->format('M d, Y H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->user)
                                <div class="font-bold text-gray-900">{{ $log->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                            @else
                                <span class="text-gray-400 italic">System / Unknown</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-teal-50 text-[#00796B] border border-teal-200">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            {{ $log->target_type ? class_basename($log->target_type) . ' #' . $log->target_id : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-xs font-mono max-w-xs truncate">
                            {{ json_encode($log->details) }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">
                            {{ $log->ip_address ?? '127.0.0.1' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No administrative audit logs found yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($logs->hasPages())
            <div class="p-4 border-t">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
