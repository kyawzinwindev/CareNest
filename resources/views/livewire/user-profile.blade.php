<div class="relative min-h-[90vh] py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Glow effects (Antigravity Theme) -->
    <div class="absolute top-10 left-1/4 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[6000ms]"></div>
    <div class="absolute bottom-10 right-1/4 w-[500px] h-[500px] bg-violet-600/10 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[8000ms]"></div>

    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="mb-10 text-center sm:text-left">
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight mb-2 font-['Outfit'] bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">
                My Profile & Security
            </h1>
            <p class="text-slate-400 text-sm sm:text-base font-light">
                Manage your metrics and secure your client-side patient account.
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Patient Information Card -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Edit Form -->
                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/80 rounded-3xl shadow-2xl p-6 sm:p-10 hover:border-cyan-500/20 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/60">
                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white font-['Outfit']">Profile Metrics</h2>
                            <p class="text-xs text-slate-500">Update your clinical weight, height, and age metadata.</p>
                        </div>
                    </div>

                    @if (session()->has('profile_success'))
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-semibold">
                            {{ session('profile_success') }}
                        </div>
                    @endif

                    <form wire:submit="updateProfile" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</label>
                                <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 focus:ring-1 focus:ring-cyan-500/60 transition-all" placeholder="John Doe">
                                @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                                <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 focus:ring-1 focus:ring-cyan-500/60 transition-all" placeholder="john@example.com">
                                @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date of Birth</label>
                                <input wire:model="dob" type="date" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 focus:outline-none focus:border-cyan-500/60 focus:ring-1 focus:ring-cyan-500/60 transition-all">
                                @error('dob') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Weight (kg)</label>
                                <input wire:model="weight" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 focus:ring-1 focus:ring-cyan-500/60 transition-all" placeholder="70.5">
                                @error('weight') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Height (cm)</label>
                                <input wire:model="height" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 focus:ring-1 focus:ring-cyan-500/60 transition-all" placeholder="175">
                                @error('height') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 hover:scale-105 active:scale-95 transition-all duration-200 shadow-xl shadow-cyan-500/10">
                                Save Profile Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side: Security Password Card -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/80 rounded-3xl shadow-2xl p-6 sm:p-8 hover:border-violet-500/20 transition-all duration-300 h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800/60">
                            <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white font-['Outfit']">Change Password</h2>
                                <p class="text-xs text-slate-500">Ensure security via strong credentials.</p>
                            </div>
                        </div>

                        @if (session()->has('password_success'))
                            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-semibold">
                                {{ session('password_success') }}
                            </div>
                        @endif

                        <form wire:submit="updatePassword" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Current Password</label>
                                <input wire:model="currentPassword" type="password" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-violet-500/60 focus:ring-1 focus:ring-violet-500/60 transition-all" placeholder="••••••••">
                                @error('currentPassword') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">New Password</label>
                                <input wire:model="newPassword" type="password" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-violet-500/60 focus:ring-1 focus:ring-violet-500/60 transition-all" placeholder="Min. 8 characters">
                                @error('newPassword') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Confirm New Password</label>
                                <input wire:model="confirmNewPassword" type="password" class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-violet-500/60 focus:ring-1 focus:ring-violet-500/60 transition-all" placeholder="Re-type password">
                                @error('confirmNewPassword') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold rounded-xl text-slate-900 bg-gradient-to-r from-violet-400 to-indigo-500 hover:from-violet-300 hover:to-indigo-400 hover:scale-105 active:scale-95 transition-all duration-200 shadow-xl shadow-violet-500/10">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
