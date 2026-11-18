@extends('layouts.admin', ['title' => 'Executive Dashboard'])

@section('header_title', 'Operational Overview')

@section('header_actions')
    <a href="{{ route('admin.providers.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 hover:from-amber-400 hover:via-orange-400 hover:to-rose-400 text-white shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>Pending KYC ({{ $pendingVerifications }})</span>
    </a>
@endsection

@section('content')
    <!-- METRIC CARDS ROW WITH VIBRANT ACCENTS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Revenue -->
        <div class="relative overflow-hidden rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 group hover:border-violet-500/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-violet-600/10 rounded-full blur-2xl group-hover:bg-violet-600/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Captured Revenue</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2">₹{{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="w-13 h-13 p-3 rounded-2xl bg-gradient-to-tr from-violet-600 via-purple-600 to-indigo-500 text-white shadow-lg shadow-purple-600/30 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs text-emerald-400 font-semibold">
                <span class="flex items-center">↑ 18.2%</span>
                <span class="text-slate-500 font-normal">from last cycle</span>
            </div>
        </div>

        <!-- Card 2: Total Bookings -->
        <div class="relative overflow-hidden rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 group hover:border-cyan-500/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Bookings</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2">{{ number_format($totalBookings) }}</h3>
                </div>
                <div class="w-13 h-13 p-3 rounded-2xl bg-gradient-to-tr from-cyan-500 via-teal-500 to-blue-600 text-white shadow-lg shadow-cyan-500/30 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs text-cyan-400 font-semibold">
                <span>Real-time state machine</span>
            </div>
        </div>

        <!-- Card 3: Approved Providers -->
        <div class="relative overflow-hidden rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 group hover:border-emerald-500/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Verified Pros</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2">{{ number_format($activeProviders) }}</h3>
                </div>
                <div class="w-13 h-13 p-3 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/30 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs text-emerald-400 font-semibold">
                <span>Background checked & verified</span>
            </div>
        </div>

        <!-- Card 4: Pending KYC Queue -->
        <div class="relative overflow-hidden rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 group hover:border-amber-500/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">KYC In Queue</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2">{{ number_format($pendingVerifications) }}</h3>
                </div>
                <div class="w-13 h-13 p-3 rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-rose-600 text-white shadow-lg shadow-orange-500/30 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs text-amber-400 font-semibold">
                <span>Requires admin review</span>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS WITH VIBRANT GRADIENT BUTTONS -->
    <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-slate-900/95 to-slate-900 border border-slate-800/80 p-6">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-fuchsia-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            Command Dispatch Actions
        </h3>
        <div class="flex flex-wrap gap-3.5">
            <a href="{{ route('admin.services.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 hover:from-violet-500 hover:via-fuchsia-500 hover:to-pink-500 text-white shadow-lg shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-2">
                <span>+ Create Category / Service</span>
            </a>

            <a href="{{ route('admin.providers.index', ['status' => 'pending']) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-amber-500 via-orange-500 to-rose-600 hover:from-amber-400 hover:via-orange-400 hover:to-rose-500 text-white shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-2">
                <span>Approve Provider KYC ({{ $pendingVerifications }})</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-cyan-500 via-teal-500 to-blue-600 hover:from-cyan-400 hover:via-teal-400 hover:to-blue-500 text-white shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-2">
                <span>Active Dispatch Center</span>
            </a>

            <a href="{{ route('admin.tickets.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-400 hover:to-rose-500 text-white shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex items-center gap-2">
                <span>Dispute Resolution</span>
            </a>
        </div>
    </div>

    <!-- TWO COLUMN SPLIT: RECENT BOOKINGS & KYC QUEUE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings Table (2 cols) -->
        <div class="lg:col-span-2 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>Recent Marketplace Bookings</span>
                        <span class="text-xs px-2 py-0.5 rounded-md bg-slate-800 text-slate-400 font-semibold">{{ $recentBookings->count() }}</span>
                    </h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-fuchsia-400 hover:text-fuchsia-300">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-800/80 pb-3">
                            <tr>
                                <th class="py-3 px-3">Booking ID</th>
                                <th class="py-3 px-3">Customer</th>
                                <th class="py-3 px-3">Provider</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3">Total</th>
                                <th class="py-3 px-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 font-medium text-slate-300">
                            @forelse ($recentBookings as $b)
                                <tr class="hover:bg-slate-800/30 transition">
                                    <td class="py-3.5 px-3 font-mono text-slate-400">
                                        #{{ substr($b->id, 0, 8) }}
                                    </td>
                                    <td class="py-3.5 px-3 font-semibold text-white">
                                        {{ $b->customer->name ?? 'Guest User' }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        {{ $b->provider->display_name ?? 'Searching...' }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @php
                                            $badgeClasses = match($b->status) {
                                                'completed' => 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white',
                                                'in_progress', 'assigned' => 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white',
                                                'searching' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
                                                'cancelled', 'failed' => 'bg-gradient-to-r from-rose-500 to-red-600 text-white',
                                                default => 'bg-slate-700 text-slate-300',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClasses }}">
                                            {{ $b->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 font-bold text-white">
                                        ₹{{ number_format($b->total, 2) }}
                                    </td>
                                    <td class="py-3.5 px-3 text-right">
                                        <a href="{{ route('admin.bookings.index', ['status' => $b->status]) }}" class="px-3 py-1.5 rounded-lg text-[11px] font-bold bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white shadow-sm transition">
                                            Inspect
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-500">
                                        No bookings recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pending KYC Queue Widget (1 col) -->
        <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span>KYC Review Queue</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400 font-bold border border-amber-500/30">
                            {{ $pendingDocuments->count() }} Pending
                        </span>
                    </h3>
                    <a href="{{ route('admin.providers.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-fuchsia-400 hover:text-fuchsia-300">View All &rarr;</a>
                </div>

                <div class="space-y-3.5">
                    @forelse ($pendingDocuments as $doc)
                        <div class="p-3.5 rounded-xl bg-slate-950/60 border border-slate-800 flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-white">{{ $doc->provider->display_name ?? 'Provider' }}</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-slate-800 text-slate-400">
                                    {{ str_replace('_', ' ', $doc->doc_type) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 truncate">Doc #: {{ $doc->doc_number ?? 'Not provided' }}</p>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 gap-2">
                                <a href="{{ route('admin.providers.show', $doc->provider_id) }}" class="text-[11px] font-semibold text-cyan-400 hover:underline">
                                    View File &rarr;
                                </a>

                                <div class="flex items-center gap-1.5">
                                    <form method="POST" action="{{ route('admin.providers.verify-doc', $doc->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white shadow-md shadow-emerald-500/20">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.providers.verify-doc', $doc->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white shadow-md shadow-rose-500/20">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-500">
                            <svg class="w-10 h-10 text-emerald-500/40 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p class="text-xs">No pending verification documents!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

