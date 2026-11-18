@extends('layouts.admin', ['title' => 'Services Catalog Management'])

@section('header_title', 'Marketplace Services Catalog')

@section('header_actions')
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-800 text-slate-300 hover:text-white transition">
        &larr; Back to Dashboard
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- LEFT: Add New Category & Service Forms -->
        <div class="space-y-6">
            <!-- Form 1: Add Category -->
            <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500"></span>
                    Create Category
                </h3>

                <form method="POST" action="{{ route('admin.services.category.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Category Name</label>
                        <input type="text" name="name" required placeholder="e.g. Electrician, Plumbing, Beauty" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white placeholder-slate-600 focus:border-fuchsia-500 focus:ring-1 focus:ring-fuchsia-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Parent Category (Optional)</label>
                        <select name="parent_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-300 focus:border-fuchsia-500 outline-none">
                            <option value="">-- None (Top Level) --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="requires_license" name="requires_license" value="1" class="rounded bg-slate-950 border-slate-800 text-fuchsia-600 focus:ring-fuchsia-500">
                        <label for="requires_license" class="text-xs text-slate-300">Requires verified license (e.g. Doctor, Electrician)</label>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 hover:from-violet-500 hover:via-fuchsia-500 hover:to-pink-500 text-white shadow-lg shadow-fuchsia-600/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                        Create Category
                    </button>
                </form>
            </div>

            <!-- Form 2: Add Service -->
            <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gradient-to-r from-cyan-400 to-blue-600"></span>
                    Add Service to Catalog
                </h3>

                <form method="POST" action="{{ route('admin.services.service.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Category</label>
                        <select name="category_id" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-300 focus:border-cyan-500 outline-none">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Service Name</label>
                        <input type="text" name="name" required placeholder="e.g. AC Deep Cleaning, Tap Leakage Repair" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-white placeholder-slate-600 focus:border-cyan-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Pricing Model</label>
                            <select name="pricing_model" required class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 focus:border-cyan-500 outline-none">
                                <option value="fixed">Fixed Price</option>
                                <option value="hourly">Hourly Rate</option>
                                <option value="per_unit">Per Unit</option>
                                <option value="quote">Quote on Visit</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mode</label>
                            <select name="mode" required class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 focus:border-cyan-500 outline-none">
                                <option value="at_customer">At Customer</option>
                                <option value="at_provider">At Provider</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Base Price (₹)</label>
                            <input type="number" step="0.01" name="base_price" placeholder="499.00" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-600 focus:border-cyan-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">City</label>
                            <select name="city_id" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 focus:border-cyan-500 outline-none">
                                <option value="">-- Optional --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-cyan-500 via-teal-500 to-blue-600 hover:from-cyan-400 hover:via-teal-400 hover:to-blue-500 text-white shadow-lg shadow-cyan-500/25 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                        Add Service
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT: Current Catalog Hierarchy (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            @forelse ($categories as $cat)
                <div class="rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-slate-800/80 p-6 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-pink-500 flex items-center justify-center font-bold text-white shadow-md shadow-pink-500/20">
                                {{ strtoupper(substr($cat->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-white">{{ $cat->name }}</h4>
                                <span class="text-xs text-slate-400 font-mono">{{ $cat->services->count() }} services configured</span>
                            </div>
                        </div>

                        @if ($cat->requires_license)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                License Required
                            </span>
                        @endif
                    </div>

                    <!-- Services list under category -->
                    <div class="divide-y divide-slate-800/60">
                        @forelse ($cat->services as $service)
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h5 class="text-sm font-bold text-white">{{ $service->name }}</h5>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-800 text-slate-300 uppercase tracking-wide">
                                            {{ $service->pricing_model }}
                                        </span>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-800 text-cyan-400">
                                            {{ $service->mode }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5">Estimated Duration: {{ $service->duration_min ?? 60 }} mins</p>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($service->cityPrices->first())
                                        <span class="text-sm font-bold text-emerald-400 font-mono">
                                            ₹{{ number_format($service->cityPrices->first()->base_price, 2) }}
                                        </span>
                                    @endif

                                    <form method="POST" action="{{ route('admin.services.toggle', $service->id) }}">
                                        @csrf
                                        @if ($service->is_active)
                                            <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md shadow-emerald-500/20 hover:opacity-90">
                                                Active
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-slate-800 text-slate-400 hover:text-white">
                                                Inactive
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 py-3 italic">No services created under this category yet.</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500 bg-slate-900/40 rounded-2xl border border-slate-800">
                    No categories or services found. Create one using the form on the left!
                </div>
            @endforelse
        </div>
    </div>
@endsection

