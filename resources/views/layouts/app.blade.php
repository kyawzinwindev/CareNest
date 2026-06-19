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
                    <a href="{{ url('/') }}#specialties" class="text-slate-300 hover:text-white transition-colors">Specialties</a>
                    <a href="{{ url('/') }}#features" class="text-slate-300 hover:text-white transition-colors">Features</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-slate-400 hidden sm:inline">Hello, <strong class="text-slate-200">{{ auth()->user()->name }}</strong></span>
                            @if(auth()->user()->role !== App\Enums\Role::PATIENT)
                                <a href="{{ url('/admin') }}" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-200 px-3 py-1.5 rounded-lg border border-slate-700 transition-colors">
                                    Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-slate-400 hover:text-rose-400 transition-colors font-medium">
                                    Log out
                                </button>
                            </form>
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
