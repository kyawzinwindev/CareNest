<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CareNest') }} - Online Clinic Appointment System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-['Plus_Jakarta_Sans'] selection:bg-cyan-500 selection:text-slate-900 overflow-x-hidden antialiased">
    <!-- Sticky Glassmorphic Navbar -->
    <header class="fixed top-0 inset-x-0 z-50 bg-slate-950/40 backdrop-blur-md border-b border-slate-900/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-violet-600 flex items-center justify-center shadow-lg shadow-cyan-500/20 group-hover:scale-105 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-slate-900">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21L14.907 18M21.907 18 19.5 21L17.1 18M18 14l-3-6H9l-3 6m12 0h-3.907M6 14H2.1M3.5 10.5h17M6 5.5l3-3h6l3 3M8.5 2.5h7" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent group-hover:opacity-90 transition-opacity">CareNest</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ url('/') }}" class="text-slate-300 hover:text-white transition-colors">Home</a>
                    <a href="{{ url('/') }}#about" class="text-slate-300 hover:text-white transition-colors">Our CareNest</a>
                    <a href="{{ url('/') }}#specialties" class="text-slate-300 hover:text-white transition-colors">Specialties</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->role === App\Enums\Role::PATIENT)
                            <livewire:notification-bell />
                        @endif

                        <!-- Alpine.js User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1.5 rounded-xl border border-slate-800 bg-slate-900/60 hover:bg-slate-800/80 hover:border-slate-700 transition-all focus:outline-none">
                                <!-- Profile Icon -->
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-cyan-400 to-violet-600 flex items-center justify-center text-slate-900 font-extrabold text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-semibold text-slate-300 mr-1 hidden sm:inline">{{ auth()->user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                 class="absolute right-0 mt-2 w-52 rounded-2xl bg-slate-900/95 backdrop-blur-md border border-slate-800/80 shadow-2xl p-2 z-50"
                                 style="display: none;">
                                <div class="px-3 py-2 border-b border-slate-800/60 mb-1">
                                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">Logged in as</p>
                                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-cyan-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    My Profile & Security
                                </a>
                                @if(auth()->user()->role === App\Enums\Role::PATIENT)
                                    <a href="{{ route('appointments') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-violet-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        My Appointments
                                    </a>
                                @else
                                    <a href="{{ url('/admin') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                        </svg>
                                        Filament Dashboard
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 px-3 py-2.5 rounded-xl text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition-colors text-left font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-slate-300 hover:text-white transition-colors font-medium">Log in</a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-block text-sm text-slate-300 hover:text-white transition-colors font-medium">Register</a>
                    @endauth

                    <a href="{{ url('/booking') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md shadow-cyan-500/10">
                        Book Appointment
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="pt-16 sm:pt-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900/60 py-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
            <p>&copy; {{ date('Y') }} CareNest Clinic. All rights reserved.</p>
            <p class="mt-2 text-xs text-slate-600">Premium Antigravity Interface Design for Patients</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
