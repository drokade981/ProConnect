@extends('layouts.admin', ['title' => 'Review Provider: ' . $provider->display_name])

@section('header_title', 'Provider Profile & KYC Audit')

@section('header_actions')
    <a href="{{ route('admin.providers.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white transition">
        &larr; Back to List
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT: Provider Details Card -->
        <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 space-y-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-violet-600 via-fuchsia-600 to-pink-500 p-0.5 shadow-lg shadow-fuchsia-600/30">
                    <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center font-bold text-2xl text-white">
                        {{ strtoupper(substr($provider->display_name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $provider->display_name }}</h3>
                    <p class="text-xs text-slate-400 font-mono">{{ $provider->user->phone ?? 'No Phone' }}</p>
                    <p class="text-xs text-slate-400">{{ $provider->user->email ?? 'No Email' }}</p>
                </div>
            </div>

            <!-- Current Overall Status -->
            <div class="p-4 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Verification Status:</span>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-amber-500 to-orange-500 text-white">
                        {{ $provider->verification }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Onboarding Date:</span>
                    <span class="font-semibold text-slate-200">{{ $provider->onboarded_at ? $provider->onboarded_at->format('M d, Y') : 'Pending' }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400">Experience:</span>
                    <span class="font-semibold text-slate-200">{{ $provider->experience_months }} months</span>
                </div>
            </div>

            <!-- Direct Verification Override Form -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Direct Status Override</h4>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.providers.update-status', $provider->user_id) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="verification" value="approved">
                        <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white shadow-lg shadow-emerald-500/25 hover:-translate-y-0.5 transition-all duration-200">
                            Approve Pro
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.providers.update-status', $provider->user_id) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="verification" value="rejected">
                        <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white shadow-lg shadow-rose-500/25 hover:-translate-y-0.5 transition-all duration-200">
                            Reject Pro
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bank & Settlement Info -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Settlement & Bank Details</h4>
                @forelse ($provider->bankAccounts as $bank)
                    <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800 text-xs space-y-1">
                        <p class="font-bold text-white">{{ $bank->account_name ?? 'Bank Account' }}</p>
                        <p class="text-slate-400">IFSC: <span class="font-mono text-slate-300">{{ $bank->ifsc ?? 'N/A' }}</span></p>
                        @if ($bank->upi_id)
                            <p class="text-slate-400">UPI ID: <span class="font-mono text-cyan-400">{{ $bank->upi_id }}</span></p>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-500 italic">No bank accounts linked yet.</p>
                @endforelse
            </div>
        </div>

        <!-- RIGHT: KYC Documents Review (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6">
                <h3 class="text-base font-bold text-white mb-2 flex items-center gap-2">
                    <span>Uploaded KYC & Licensing Documents</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-300">{{ $provider->documents->count() }}</span>
                </h3>
                <p class="text-xs text-slate-400 mb-6">Verify government IDs (Aadhaar, PAN), police verifications, and professional licenses (medical, electrical, engineering).</p>

                <div class="space-y-4">
                    @forelse ($provider->documents as $doc)
                        <div class="p-5 rounded-2xl bg-slate-950/60 border border-slate-800/90 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-white uppercase tracking-wide">
                                        {{ str_replace('_', ' ', $doc->doc_type) }}
                                    </h4>
                                    @php
                                        $docBadge = match($doc->status) {
                                            'approved' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            'rejected' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                                            default => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                        };
                                    @endphp
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $docBadge }}">
                                        {{ $doc->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 font-mono">Doc / Reg #: <span class="text-slate-200">{{ $doc->doc_number ?? 'N/A' }}</span></p>
                                @if ($doc->issued_by)
                                    <p class="text-xs text-slate-400">Issuer: {{ $doc->issued_by }}</p>
                                @endif
                                @if ($doc->expires_on)
                                    <p class="text-xs text-slate-400">Expires: {{ $doc->expires_on->format('M d, Y') }}</p>
                                @endif
                                <a href="{{ $doc->file_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-cyan-400 hover:text-cyan-300 pt-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    <span>Open Document Media</span>
                                </a>
                            </div>

                            <!-- Document Verification Gradient Actions -->
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('admin.providers.verify-doc', $doc->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white shadow-lg shadow-emerald-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.providers.verify-doc', $doc->id) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-400 hover:to-red-500 text-white shadow-lg shadow-rose-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 bg-slate-950/40 rounded-xl border border-slate-800">
                            No documents submitted yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Services Provided -->
            <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6">
                <h3 class="text-base font-bold text-white mb-4">Assigned Skills & Services</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse ($provider->services as $ps)
                        <span class="px-3 py-1.5 rounded-xl bg-slate-800 text-xs font-semibold text-slate-200 border border-slate-700 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-fuchsia-400"></span>
                            {{ $ps->service->name ?? 'Service' }}
                            @if ($ps->custom_price)
                                <span class="text-emerald-400 font-bold font-mono">₹{{ number_format($ps->custom_price, 2) }}</span>
                            @endif
                        </span>
                    @empty
                        <p class="text-xs text-slate-500">No skills/services linked yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

