@extends('layouts.admin', ['title' => 'Support & Disputes'])

@section('header_title', 'Support Tickets & Disputes')

@section('header_actions')
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
    <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-800 pb-3">
                    <tr>
                        <th class="py-3 px-3">Ticket ID</th>
                        <th class="py-3 px-3">Category</th>
                        <th class="py-3 px-3">Raised By</th>
                        <th class="py-3 px-3">Booking #</th>
                        <th class="py-3 px-3">Description</th>
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-medium text-slate-300">
                    @forelse ($tickets as $ticket)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-4 px-3 font-mono text-slate-400">
                                #{{ substr($ticket->id, 0, 8) }}
                            </td>
                            <td class="py-4 px-3">
                                @php
                                    $catColor = match($ticket->category) {
                                        'safety', 'damage' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                        'overcharge' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                        default => 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $catColor }}">
                                    {{ str_replace('_', ' ', $ticket->category) }}
                                </span>
                            </td>
                            <td class="py-4 px-3 font-semibold text-white">
                                {{ $ticket->raisedByUser->name ?? 'User' }}
                            </td>
                            <td class="py-4 px-3 font-mono text-slate-400">
                                @if ($ticket->booking_id)
                                    #{{ substr($ticket->booking_id, 0, 8) }}
                                @else
                                    General
                                @endif
                            </td>
                            <td class="py-4 px-3 max-w-xs truncate text-slate-400">
                                {{ $ticket->description }}
                            </td>
                            <td class="py-4 px-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $ticket->status === 'resolved' ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white' : 'bg-gradient-to-r from-amber-500 to-orange-500 text-white' }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="py-4 px-3 text-right">
                                @if ($ticket->status !== 'resolved')
                                    <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white shadow-md shadow-emerald-500/20 hover:-translate-y-0.5 transition-all duration-200">
                                            Mark Resolved
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500 font-mono">Resolved</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                No support tickets currently open.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    </div>
@endsection

