@extends('layouts.admin', ['title' => 'Providers & KYC Management'])

@section('header_title', 'Professionals & KYC Hub')

@section('header_actions')
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
    <!-- FILTER PILLS ROW -->
    <div class="flex flex-wrap items-center justify-between gap-4 pb-2">
        <div class="flex flex-wrap items-center gap-2">
            @php
                $filters = [
                    'all' => ['label' => 'All Professionals', 'count' => $counts['all']],
                    'pending' => ['label' => 'Pending Review', 'count' => $counts['pending']],
                    'approved' => ['label' => 'Approved & Verified', 'count' => $counts['approved']],
                    'rejected' => ['label' => 'Rejected', 'count' => $counts['rejected']],
                ];
            @endphp

            @foreach ($filters as $key => $item)
                <a href="{{ route('admin.providers.index', ['status' => $key]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 flex items-center gap-2 {{ $status === $key ? 'bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 text-white shadow-lg shadow-fuchsia-600/30' : 'bg-slate-900/90 text-slate-400 hover:text-white border border-slate-800' }}">
                    <span>{{ $item['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $status === $key ? 'bg-white/20 text-white' : 'bg-slate-800 text-slate-400' }}">
                        {{ $item['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- PROVIDERS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($providers as $provider)
            <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 flex flex-col justify-between hover:border-violet-500/50 transition-all duration-300 group">
                <div>
                    <!-- Header with Status Badge -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-violet-600 via-purple-600 to-pink-500 p-0.5 shadow-md shadow-purple-600/30">
                                <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center font-bold text-base text-white">
                                    {{ strtoupper(substr($provider->display_name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white group-hover:text-fuchsia-400 transition">{{ $provider->display_name }}</h4>
                                <p class="text-xs text-slate-400">{{ $provider->user->email ?? $provider->user->phone ?? 'Provider' }}</p>
                            </div>
                        </div>

                        <!-- Verification Status Pill -->
                        @php
                            $badge = match($provider->verification) {
                                'approved' => 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white',
                                'pending' => 'bg-gradient-to-r from-amber-500 to-orange-500 text-white',
                                'rejected' => 'bg-gradient-to-r from-rose-500 to-red-600 text-white',
                                default => 'bg-slate-700 text-slate-300',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badge }} shadow-sm">
                            {{ $provider->verification }}
                        </span>
                    </div>

                    <!-- Metrics bar -->
                    <div class="grid grid-cols-3 gap-2 my-5 p-3 rounded-xl bg-slate-950/70 border border-slate-800 text-center">
                        <div>
                            <span class="text-[10px] uppercase font-semibold text-slate-500">Rating</span>
                            <p class="text-sm font-bold text-amber-400 flex items-center justify-center gap-1">
                                ★ {{ number_format($provider->avg_rating, 1) }}
                            </p>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-semibold text-slate-500">Jobs Done</span>
                            <p class="text-sm font-bold text-white">{{ $provider->jobs_completed }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-semibold text-slate-500">Accept %</span>
                            <p class="text-sm font-bold text-emerald-400">{{ number_format($provider->acceptance_rate, 0) }}%</p>
                        </div>
                    </div>

                    <!-- KYC Documents status summary -->
                    <div class="text-xs text-slate-400 space-y-1 mb-5">
                        <div class="flex justify-between items-center">
                            <span>Uploaded Documents:</span>
                            <span class="font-bold text-slate-200">{{ $provider->documents->count() }} submitted</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Provider Type:</span>
                            <span class="font-bold uppercase tracking-wider text-[11px] text-fuchsia-400">{{ $provider->kind }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('admin.providers.show', $provider->user_id) }}" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-center bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 hover:from-violet-500 hover:via-fuchsia-500 hover:to-pink-500 text-white shadow-lg shadow-fuchsia-600/25 hover:shadow-fuchsia-600/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                    Review Documents & Profile &rarr;
                </a>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-500 bg-slate-900/40 rounded-2xl border border-slate-800">
                <p class="text-sm">No professionals found under "{{ $status }}" filter.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $providers->links() }}
    </div>
@endsection

