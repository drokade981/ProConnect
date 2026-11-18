@extends('layouts.admin', ['title' => 'Live Bookings Dispatch'])

@section('header_title', 'Live Booking & Dispatch Control')

@section('header_actions')
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
    <!-- STATUS FILTER PILLS WITH VIBRANT COLORS -->
    <div class="flex flex-wrap items-center justify-between gap-4 pb-2">
        <div class="flex flex-wrap items-center gap-2">
            @php
                $statusTabs = [
                    'all' => ['label' => 'All Bookings', 'count' => $statusCounts['all']],
                    'searching' => ['label' => 'Searching Pro', 'count' => $statusCounts['searching']],
                    'assigned' => ['label' => 'Assigned', 'count' => $statusCounts['assigned']],
                    'in_progress' => ['label' => 'In Progress', 'count' => $statusCounts['in_progress']],
                    'completed' => ['label' => 'Completed', 'count' => $statusCounts['completed']],
                    'cancelled' => ['label' => 'Cancelled', 'count' => $statusCounts['cancelled']],
                ];
            @endphp

            @foreach ($statusTabs as $key => $tab)
                <a href="{{ route('admin.bookings.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 {{ $status === $key ? 'bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 text-white shadow-lg shadow-fuchsia-600/30' : 'bg-slate-900/90 text-slate-400 hover:text-white border border-slate-800' }}">
                    <span>{{ $tab['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $status === $key ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        {{ $tab['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- BOOKINGS TABLE CARD -->
    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-800 pb-3">
                    <tr>
                        <th class="py-3 px-3">Booking Reference</th>
                        <th class="py-3 px-3">Customer</th>
                        <th class="py-3 px-3">Assigned Pro</th>
                        <th class="py-3 px-3">City & Address</th>
                        <th class="py-3 px-3">Scheduled Slot</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Total Amount</th>
                        <th class="py-3 px-3 text-right">Quick Override</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-medium text-slate-300">
                    @forelse ($bookings as $b)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-4 px-3 font-mono text-slate-400">
                                <span class="font-bold text-white">#{{ substr($b->id, 0, 8) }}</span>
                                <span class="block text-[10px] text-slate-500 font-sans">{{ $b->created_at ? $b->created_at->diffForHumans() : '' }}</span>
                            </td>
                            <td class="py-4 px-3">
                                <p class="font-bold text-white">{{ $b->customer->name ?? 'Guest User' }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $b->customer->phone ?? 'N/A' }}</p>
                            </td>
                            <td class="py-4 px-3">
                                @if ($b->provider)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-fuchsia-600 to-indigo-600 flex items-center justify-center text-[10px] text-white font-bold">
                                            {{ strtoupper(substr($b->provider->display_name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-white">{{ $b->provider->display_name }}</span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                        Dispatching...
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-3 text-slate-400">
                                {{ $b->address->city->name ?? 'City' }} ({{ $b->address->pincode ?? 'IN' }})
                            </td>
                            <td class="py-4 px-3 text-slate-300">
                                {{ $b->scheduled_start ? $b->scheduled_start->format('M d, H:i') : 'Flexible' }}
                            </td>
                            <td class="py-4 px-3">
                                @php
                                    $badge = match($b->status) {
                                        'completed' => 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white',
                                        'in_progress', 'en_route', 'assigned' => 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white',
                                        'searching' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
                                        'cancelled', 'failed' => 'bg-gradient-to-r from-rose-500 to-red-600 text-white',
                                        default => 'bg-slate-700 text-slate-300',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badge }} shadow-sm">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="py-4 px-3 font-bold text-white font-mono text-sm">
                                ₹{{ number_format($b->total, 2) }}
                            </td>
                            <td class="py-4 px-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if ($b->status !== 'completed' && $b->status !== 'cancelled')
                                        <form method="POST" action="{{ route('admin.bookings.update-status', $b->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md shadow-emerald-500/20 hover:opacity-90">
                                                Complete
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.bookings.update-status', $b->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-rose-500 to-red-600 text-white shadow-md shadow-rose-500/20 hover:opacity-90">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-slate-500 font-mono">Finalized</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-500">
                                No bookings found for status "{{ $status }}".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection

