@extends('layouts.app')
@section('title','Payment History')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="bg-orange-500/15 text-orange-400 text-xs px-2.5 py-0.5 rounded-full border border-orange-500/30">Admin</span>
            <h1 class="font-display text-3xl font-bold text-white mt-2">Payments</h1>
            <p class="text-white/50 mt-1">
                Total Revenue: <span class="text-green-400 font-semibold">NPR {{ number_format($totalRevenue) }}</span>
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="border border-white/10 text-white/60 hover:text-white px-4 py-2.5 rounded-xl text-sm transition-all">← Dashboard</a>
    </div>

    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex gap-3 mb-6">
        <select name="status" onchange="this.form.submit()"
                class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-orange-500/60 transition-all">
            <option value="">All Status</option>
            @foreach(['pending','completed','failed','refunded'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @if(request('status'))
            <a href="{{ route('admin.payments.index') }}" class="border border-white/10 text-white/60 hover:text-white px-4 py-2.5 rounded-xl text-sm transition-all">Reset</a>
        @endif
    </form>

    <div class="bg-[#111113] border border-white/[0.08] rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-white/[0.08]">
                    <tr class="text-white/40 text-xs uppercase tracking-wider">
                        <th class="px-5 py-3.5 text-left">User</th>
                        <th class="px-4 py-3.5 text-left hidden md:table-cell">Listing</th>
                        <th class="px-4 py-3.5 text-right">Amount</th>
                        <th class="px-4 py-3.5 text-center hidden sm:table-cell">Method</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-left hidden lg:table-cell">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse($unlocks as $unlock)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4">
                            <div class="text-white font-medium">{{ $unlock->user->name }}</div>
                            <div class="text-white/30 text-xs">{{ $unlock->user->email }}</div>
                        </td>
                        <td class="px-4 py-4 hidden md:table-cell">
                            <div class="text-white/70 text-sm truncate max-w-[180px]">{{ $unlock->listing->title ?? 'Deleted' }}</div>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span class="font-semibold {{ $unlock->isCompleted()?'text-green-400':'text-white/60' }}">
                                NPR {{ number_format($unlock->amount_paid) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center hidden sm:table-cell">
                            <span class="text-white/50 text-xs border border-white/10 px-2 py-1 rounded-full capitalize">{{ $unlock->payment_method }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @php
                                $cls = match($unlock->payment_status) {
                                    'completed' => 'bg-green-500/15 text-green-400 border-green-500/30',
                                    'pending'   => 'bg-yellow-500/15 text-yellow-400 border-yellow-500/30',
                                    'failed'    => 'bg-red-500/15 text-red-400 border-red-500/30',
                                    default     => 'bg-white/10 text-white/50 border-white/20',
                                };
                            @endphp
                            <span class="{{ $cls }} text-xs px-2.5 py-0.5 rounded-full border font-medium">{{ ucfirst($unlock->payment_status) }}</span>
                        </td>
                        <td class="px-4 py-4 hidden lg:table-cell text-white/40 text-xs">
                            {{ $unlock->paid_at?->format('M d, Y H:i') ?? $unlock->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-white/30 text-sm">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $unlocks->links('partials.pagination') }}</div>
</div>
@endsection
