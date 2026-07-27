@extends('layouts.app')
@section('title','Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="bg-orange-500/15 text-orange-400 text-xs px-2.5 py-0.5 rounded-full border border-orange-500/30">Admin</span>
            <h1 class="font-display text-3xl font-bold text-white mt-2">Manage Users</h1>
            <p class="text-white/50 mt-1">{{ $users->total() }} registered users</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="border border-white/10 text-white/60 hover:text-white px-4 py-2.5 rounded-xl text-sm transition-all">← Dashboard</a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 mb-6">
        <select name="role" onchange="this.form.submit()"
                class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500/60 transition-all">
            <option value="">All Roles</option>
            @foreach(['admin','owner','user'] as $r)
                <option value="{{ $r }}" {{ request('role')==$r?'selected':'' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                   class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm flex-1 focus:outline-none focus:border-orange-500/60 placeholder-white/25 transition-all">
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white text-sm px-4 py-2.5 rounded-xl transition-all">Search</button>
            @if(request()->hasAny(['role','search']))
                <a href="{{ route('admin.users.index') }}" class="border border-white/10 text-white/60 hover:text-white px-4 py-2.5 rounded-xl text-sm transition-all">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-white/[0.08]">
                    <tr class="text-white/40 text-xs uppercase tracking-wider">
                        <th class="px-5 py-3.5 text-left">User</th>
                        <th class="px-4 py-3.5 text-left hidden sm:table-cell">Role</th>
                        <th class="px-4 py-3.5 text-center hidden md:table-cell">Listings</th>
                        <th class="px-4 py-3.5 text-center hidden md:table-cell">Unlocks</th>
                        <th class="px-4 py-3.5 text-left hidden lg:table-cell">Joined</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0
                                    {{ $user->role==='admin' ? 'bg-orange-500' : ($user->role==='owner' ? 'bg-blue-500' : 'bg-white/15') }}">
                                    {{ substr($user->name,0,1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-white font-medium truncate">{{ $user->name }}</div>
                                    <div class="text-white/30 text-xs truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 hidden sm:table-cell">
                            <span class="{{ $user->role==='admin'?'bg-orange-500/15 text-orange-400 border-orange-500/30':($user->role==='owner'?'bg-green-500/15 text-green-400 border-green-500/30':'bg-yellow-500/15 text-yellow-400 border-yellow-500/30') }} text-xs px-2.5 py-0.5 rounded-full border font-medium">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center hidden md:table-cell text-white/60">{{ $user->listings_count }}</td>
                        <td class="px-4 py-4 text-center hidden md:table-cell text-white/60">{{ $user->unlocks_count }}</td>
                        <td class="px-4 py-4 hidden lg:table-cell text-white/40 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-4 text-center">
                            @if($user->is_active)
                                <span class="bg-green-500/15 text-green-400 text-xs px-2.5 py-0.5 rounded-full border border-green-500/30 font-medium">Active</span>
                            @else
                                <span class="bg-red-500/15 text-red-400 text-xs px-2.5 py-0.5 rounded-full border border-red-500/30 font-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="border border-white/10 hover:border-orange-500/30 text-white/40 hover:text-orange-400 text-xs px-3 py-1.5 rounded-lg transition-all">View →</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-white/30 text-sm">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $users->links('partials.pagination') }}</div>
</div>
@endsection
