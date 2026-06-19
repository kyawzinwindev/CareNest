<div class="relative min-h-[75vh] flex items-center justify-center px-4 py-12">
    <!-- Glow effects -->
    <div class="absolute w-[400px] h-[400px] bg-cyan-500/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="w-full max-w-md bg-slate-900/40 backdrop-blur-md border border-slate-800/80 p-8 rounded-3xl shadow-2xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Welcome Back</h2>
            <p class="text-sm text-slate-400 mt-2 font-light">Enter your credentials to manage your clinic appointments</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                <input wire:model="email" type="email" id="email" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="name@example.com" required>
                @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                <input wire:model="password" type="password" id="password" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-3.5 px-4 font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 transition-all duration-200 shadow-lg shadow-cyan-500/10 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="login">Sign In</span>
                <span wire:loading wire:target="login" class="flex items-center gap-2">
                    <!-- Spinner -->
                    <svg class="animate-spin h-5 w-5 text-slate-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing in...
                </span>
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold transition-colors">Register here</a>
        </div>
    </div>
</div>
