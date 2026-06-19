<div class="relative min-h-[85vh] flex items-center justify-center px-4 py-12">
    <!-- Glow effects -->
    <div class="absolute w-[450px] h-[450px] bg-violet-600/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="w-full max-w-lg bg-slate-900/40 backdrop-blur-md border border-slate-800/80 p-8 rounded-3xl shadow-2xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Create Account</h2>
            <p class="text-sm text-slate-400 mt-2 font-light">Register as a patient to book appointments instantly</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</label>
                    <input wire:model="name" type="text" id="name" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="Jane Doe" required>
                    @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input wire:model="email" type="email" id="email" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="jane@example.com" required>
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <input wire:model="password" type="password" id="password" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="At least 8 characters" required>
                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="dob" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date of Birth</label>
                    <input wire:model="dob" type="date" id="dob" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" required>
                    @error('dob') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Weight (kg)</label>
                    <input wire:model="weight" type="number" step="0.1" id="weight" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="65.5" required>
                    @error('weight') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Height -->
                <div class="sm:col-span-2">
                    <label for="height" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Height (cm)</label>
                    <input wire:model="height" type="number" step="0.1" id="height" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="170" required>
                    @error('height') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full mt-6 py-3.5 px-4 font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 transition-all duration-200 shadow-lg shadow-cyan-500/10 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="register">Register Account</span>
                <span wire:loading wire:target="register" class="flex items-center gap-2">
                    <!-- Spinner -->
                    <svg class="animate-spin h-5 w-5 text-slate-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Registering...
                </span>
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold transition-colors">Sign in here</a>
        </div>
    </div>
</div>
