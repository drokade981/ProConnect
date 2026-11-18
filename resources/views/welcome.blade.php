<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'HunarApp') }} - Home</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased bg-slate-900 text-white min-h-screen flex flex-col justify-between">
        <header class="max-w-7xl mx-auto w-full p-6 flex justify-between items-center">
            <div class="text-2xl font-bold tracking-tight text-indigo-400">
                {{ config('app.name', 'HunarApp') }}
            </div>
            <nav class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 hover:from-violet-500 hover:via-fuchsia-500 hover:to-pink-500 text-white shadow-lg shadow-fuchsia-600/30 hover:-translate-y-0.5 transition-all">
                    Admin Console
                </a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-md px-4 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-md px-4 py-2 text-sm font-semibold bg-indigo-600 hover:bg-indigo-500 text-white transition">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <main class="max-w-4xl mx-auto px-6 py-16 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 mb-6">
                Laravel 13 Application 
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white mb-6">
                Hunar Services Marketplace
            </h1>
            <p class="text-lg text-slate-400 mb-8 max-w-2xl mx-auto">
                Connect customers with trusted professionals — plumbers, electricians, beauticians, cleaners, and licensed experts.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 transition">
                        Open Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 transition">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-lg bg-slate-800 text-slate-200 font-medium hover:bg-slate-700 border border-slate-700 transition">
                        Sign In
                    </a>
                @endauth
            </div>
        </main>

        <footer class="text-center py-6 text-sm text-slate-500 border-t border-slate-800">
            {{ config('app.name', 'HunarApp') }} &copy; {{ date('Y') }}
        </footer>
    </body>
</html>

