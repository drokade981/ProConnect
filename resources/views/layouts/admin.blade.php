<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Console' }} - {{ config('app.name', 'HunarApp') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            950: '#070913',
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #070913; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="bg-dark-950 text-slate-100 font-sans antialiased min-h-screen flex selection:bg-fuchsia-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Ambient Gradient Glow Effects in Background -->
    <div class="fixed top-0 left-0 w-96 h-96 bg-purple-600/15 rounded-full filter blur-3xl pointer-events-none -z-10 animate-pulse"></div>
    <div class="fixed bottom-0 right-0 w-96 h-96 bg-cyan-500/15 rounded-full filter blur-3xl pointer-events-none -z-10 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="fixed top-1/2 left-1/3 w-80 h-80 bg-pink-500/10 rounded-full filter blur-3xl pointer-events-none -z-10"></div>

    <!-- Mobile Sidebar Backdrop -->
    <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900/90 backdrop-blur-2xl border-r border-slate-800/80 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto">
        <!-- Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/70">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-violet-600 via-fuchsia-600 to-pink-500 p-0.5 shadow-lg shadow-fuchsia-500/30 group-hover:shadow-fuchsia-500/50 transition duration-300">
                    <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-fuchsia-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <span class="text-xl font-extrabold bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Urban<span class="text-fuchsia-500">Ops</span></span>
                    <span class="block text-[10px] tracking-widest font-bold uppercase text-fuchsia-400">Admin Control</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
            <p class="px-3 text-[11px] font-bold tracking-wider uppercase text-slate-500 mb-2">Overview</p>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="px-3 pt-5 text-[11px] font-bold tracking-wider uppercase text-slate-500 mb-2">Marketplace Core</p>

            <a href="{{ route('admin.providers.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.providers.*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Providers & KYC</span>
                </div>
                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white">KYC</span>
            </a>

            <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.services.*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Services Catalog</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.bookings.*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Live Bookings</span>
            </a>

            <a href="{{ route('admin.tickets.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 {{ request()->routeIs('admin.tickets.*') ? 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/25' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Support & Disputes</span>
                </div>
            </a>

            <p class="px-3 pt-5 text-[11px] font-bold tracking-wider uppercase text-slate-500 mb-2">Shortcuts</p>

            <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-xs text-slate-400 hover:text-white hover:bg-slate-800/40 transition">
                <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                <span>Customer Marketplace</span>
            </a>
        </nav>

        <!-- Current Admin User Card -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/60">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-500 to-indigo-600 flex items-center justify-center font-bold text-white shadow-md shadow-pink-500/20 shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'System Admin' }}</p>
                        <span class="inline-flex items-center px-1.5 py-0.2 text-[10px] font-semibold rounded bg-fuchsia-500/20 text-fuchsia-300 border border-fuchsia-500/30">
                            {{ ucfirst(Auth::user()->role ?? 'Admin') }}
                        </span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition duration-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- TOP NAVBAR -->
        <header class="h-20 bg-slate-900/60 backdrop-blur-xl border-b border-slate-800/80 px-4 lg:px-8 flex items-center justify-between z-30 sticky top-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div>
                    <h1 class="text-lg lg:text-xl font-extrabold text-white tracking-tight flex items-center gap-2">
                        @yield('header_title', 'Operational Overview')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live System
                        </span>
                    </h1>
                </div>
            </div>

            <!-- Top Header Action Controls -->
            <div class="flex items-center gap-3">
                @yield('header_actions')

                <!-- Quick Gradient Action -->
                <a href="{{ route('admin.services.index') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 hover:from-violet-500 hover:via-fuchsia-500 hover:to-pink-500 text-white shadow-lg shadow-fuchsia-600/30 hover:shadow-fuchsia-600/50 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Add Service</span>
                </a>
            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        @if (session('success'))
            <div class="mx-4 lg:mx-8 mt-6 p-4 rounded-2xl bg-gradient-to-r from-emerald-500/20 via-teal-500/20 to-cyan-500/20 border border-emerald-500/40 text-emerald-300 flex items-center justify-between shadow-lg shadow-emerald-500/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold">✓</div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mx-4 lg:mx-8 mt-6 p-4 rounded-2xl bg-gradient-to-r from-rose-500/20 to-red-500/20 border border-rose-500/40 text-rose-300 flex items-center justify-between shadow-lg shadow-rose-500/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-r from-rose-500 to-red-500 flex items-center justify-center text-white font-bold">!</div>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-8 space-y-8">
            @yield('content')
        </main>
    </div>

</body>
</html>

