<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                        {{ config('app.name', 'HunarApp') }}
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5">
                        {{ __('Dashboard') }}
                    </a>

                    @auth
                        @if (in_array(Auth::user()->role ?? '', ['admin', 'support']))
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 text-white shadow-md shadow-fuchsia-600/30 hover:opacity-90 transition">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Admin Console
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings / Profile & Logout (Desktop) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
                        {{ __('Log in') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 py-3 px-4 space-y-3">
        <a href="{{ route('dashboard') }}" class="block text-base font-medium {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
            {{ __('Dashboard') }}
        </a>

        @auth
            @if (in_array(Auth::user()->role ?? '', ['admin', 'support']))
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 text-white shadow-md shadow-fuchsia-600/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Admin Console
                </a>
            @endif

            <div class="pt-3 border-t border-gray-100">
                <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                <div class="mt-2 flex gap-4">
                    <a href="{{ route('profile.edit') }}" class="text-xs text-indigo-600 hover:underline">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:underline">Log Out</button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-2 border-t border-gray-100 flex gap-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Log in</a>
                <a href="{{ route('register') }}" class="text-sm text-indigo-600 font-semibold">Register</a>
            </div>
        @endauth
    </div>
</nav>
