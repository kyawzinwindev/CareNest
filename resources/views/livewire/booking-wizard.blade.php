<div class="relative min-h-[90vh] py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Glow effects -->
    <div class="absolute top-10 left-1/3 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/3 w-[500px] h-[500px] bg-violet-600/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="max-w-5xl mx-auto">
        <!-- Wizard Header / Progress Stepper -->
        <div class="mb-12">
            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-500 mb-4 px-2">
                <span class="text-cyan-400">Step {{ $step }} of 5</span>
                <span>{{ match($step) {
                    1 => 'Select Treatment',
                    2 => 'Choose Doctor',
                    3 => 'Pick Date & Time',
                    4 => 'Patient Metrics',
                    5 => 'Payment & Complete',
                    default => ''
                } }}</span>
            </div>

            <!-- Stepper Progress Bar -->
            <div class="relative w-full h-2 bg-slate-900 rounded-full overflow-hidden border border-slate-800">
                <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-cyan-400 via-violet-500 to-indigo-500 transition-all duration-500" style="width: {{ ($step - 1) * 25 }}%;"></div>
            </div>

            <!-- Steps Dots -->
            <div class="flex justify-between items-center mt-4 px-1">
                @for ($i = 1; $i <= 5; $i++)
                    <button wire:click="goToStep({{ $i }})" @if($i > $step && ($i > 4 || !$specialization || !$serviceId)) disabled @endif class="flex flex-col items-center gap-1 group focus:outline-none disabled:opacity-30 disabled:cursor-not-allowed">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center border font-bold text-xs transition-all duration-300
                            {{ $i < $step ? 'bg-gradient-to-tr from-cyan-400 to-violet-500 text-slate-900 border-transparent' : ($i === $step ? 'border-cyan-400 text-cyan-400 bg-slate-900' : 'border-slate-800 text-slate-500 bg-slate-950') }}">
                            {{ $i }}
                        </div>
                    </button>
                @endfor
            </div>
        </div>

        <!-- Session Flash Message -->
        @if (session()->has('message'))
            <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm font-medium">
                {{ session('message') }}
            </div>
        @endif

        <!-- Skeletons & Loaders -->
        <div wire:loading.delay.longest class="mb-8 p-4 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 rounded-2xl text-sm font-medium flex items-center gap-2">
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Fetching latest schedules and slots...
        </div>

        <!-- Wizard Cards Stack -->
        <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/80 rounded-3xl shadow-2xl p-6 sm:p-10">

            <!-- STEP 1: Select Specialization & Service -->
            @if ($step === 1)
                <div>
                    <h2 class="text-3xl font-extrabold text-white tracking-tight mb-8 font-['Outfit']">Select Medical Wing & Treatment</h2>

                    <!-- Specializations Selector -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                        @foreach (App\Enums\Specialization::cases() as $case)
                            <button wire:click="selectSpecialization('{{ $case->value }}')"
                                class="p-5 rounded-2xl border text-center flex flex-col items-center justify-center gap-3 transition-all duration-300 group
                                {{ $specialization === $case->value ? 'border-cyan-400 bg-cyan-500/5 text-white' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-700 hover:text-slate-200' }}">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg transition-transform group-hover:scale-115
                                    {{ $specialization === $case->value ? 'bg-cyan-500/10 text-cyan-400' : 'bg-slate-900 text-slate-500' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068" />
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-wider">{{ $case->label() }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Services List (Filtered by selected specialization) -->
                    @if ($specialization)
                        <div class="space-y-4">
                            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Available Treatments in {{ App\Enums\Specialization::from($specialization)->label() }}</h3>
                            @if ($this->services->isEmpty())
                                <div class="p-6 rounded-2xl border border-slate-800 bg-slate-950/60 text-slate-500 text-sm text-center">
                                    No services are currently configured for this specialization.
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach ($this->services as $service)
                                        <button wire:click="selectService({{ $service->id }})"
                                            class="w-full text-left p-6 rounded-2xl border bg-slate-950/60 transition-all duration-300 flex flex-col justify-between hover:border-cyan-500/40 hover:bg-slate-900/30 group
                                            {{ $serviceId === $service->id ? 'border-cyan-400 ring-1 ring-cyan-400 bg-slate-900/40' : 'border-slate-800' }}">
                                            <div>
                                                <div class="flex justify-between items-start gap-4">
                                                    <span class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $service->name }}</span>
                                                    @if ($service->required_prepayment)
                                                        <span class="text-[10px] font-semibold uppercase tracking-widest px-2 py-0.5 rounded bg-violet-500/10 border border-violet-500/20 text-violet-400 shrink-0">Prepay Req</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-slate-400 mt-2 font-light leading-relaxed">{{ $service->description }}</p>
                                            </div>
                                            <div class="mt-6 flex justify-between items-center">
                                                <span class="text-xl font-extrabold text-white font-['Outfit']">THB {{ number_format($service->price, 2) }}</span>
                                                <span class="text-xs text-cyan-400 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                                    Select Treatment &rarr;
                                                </span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-8 border border-dashed border-slate-800 bg-slate-950/40 text-slate-500 text-sm text-center rounded-2xl font-light">
                            Please select a medical wing above to browse services.
                        </div>
                    @endif
                </div>
            @endif

            <!-- STEP 2: Choose Doctor -->
            @if ($step === 2)
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Choose Specialist</h2>
                        <button wire:click="goToStep(1)" class="text-xs font-semibold text-slate-500 hover:text-slate-300 flex items-center gap-1">&larr; Back to treatments</button>
                    </div>

                    @if ($this->doctors->isEmpty())
                        <div class="p-8 border border-slate-800 bg-slate-950/60 text-slate-500 text-sm text-center rounded-2xl">
                            No specialist doctors are currently active for this specialty.
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($this->doctors as $doctor)
                                <button wire:click="selectDoctor({{ $doctor->id }})"
                                    class="w-full text-left p-6 rounded-2xl border bg-slate-950/60 transition-all duration-300 flex items-center gap-4 hover:border-cyan-500/40 hover:bg-slate-900/30 group
                                    {{ $doctorId === $doctor->id ? 'border-cyan-400 ring-1 ring-cyan-400 bg-slate-900/40' : 'border-slate-800' }}">
                                    <!-- Avatar -->
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-cyan-400 to-violet-600 flex items-center justify-center text-slate-900 font-black text-xl shrink-0 group-hover:scale-105 transition-transform">
                                        {{ substr($doctor->user->name, 0, 2) }}
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-white group-hover:text-cyan-400 transition-colors">{{ $doctor->user->name }}</h3>
                                        <span class="text-xs text-slate-500 font-semibold block uppercase tracking-wider mt-0.5">{{ $doctor->specialization->label() }}</span>
                                        <div class="mt-3 inline-flex items-center gap-1 text-[11px] font-semibold text-cyan-400/80 bg-cyan-950/20 border border-cyan-800/30 px-2 py-0.5 rounded-md">
                                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                                            Schedules Active
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- STEP 3: Date & Time Slot Selection -->
            @if ($step === 3)
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Select Date & Time Slot</h2>
                        <button wire:click="goToStep(2)" class="text-xs font-semibold text-slate-500 hover:text-slate-300 flex items-center gap-1">&larr; Back to doctors</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Date Picker column -->
                        <div class="md:col-span-1 space-y-4">
                            <label for="date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Available Schedule Dates</label>
                            @if ($this->availableDates->isEmpty())
                                <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/60 text-slate-500 text-xs text-center font-light">
                                    No schedules active for this doctor.
                                </div>
                            @else
                                <div class="flex flex-col gap-2">
                                    @foreach ($this->availableDates as $availDate)
                                        <button wire:click="$set('date', '{{ $availDate }}')"
                                            class="w-full text-left px-4 py-3 rounded-xl border text-sm font-semibold transition-all
                                            {{ $date === $availDate ? 'border-cyan-400 bg-cyan-500/5 text-white' : 'border-slate-800 bg-slate-950 text-slate-400 hover:border-slate-700 hover:text-slate-200' }}">
                                            {{ Carbon\Carbon::parse($availDate)->format('M d, Y') }} ({{ Carbon\Carbon::parse($availDate)->format('l') }})
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Time Slots column -->
                        <div class="md:col-span-2 space-y-4">
                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Available Time Slots</label>
                            @if (!$date)
                                <div class="p-8 border border-slate-800 bg-slate-950/40 text-slate-500 text-sm text-center rounded-2xl font-light">
                                    Please select a date from the left column first.
                                </div>
                            @elseif ($this->timeSlots->isEmpty())
                                <div class="p-8 border border-slate-800 bg-slate-950/60 text-slate-500 text-sm text-center rounded-2xl">
                                    No time slots found for this date.
                                </div>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach ($this->timeSlots as $slot)
                                        @if ($slot->status === App\Enums\TimeSlotStatus::AVAILABLE)
                                            <button wire:click="selectTimeSlot({{ $slot->id }})"
                                                class="px-4 py-3.5 rounded-xl border text-center text-sm font-semibold transition-all
                                                {{ $timeSlotId === $slot->id ? 'border-cyan-400 bg-cyan-500/10 text-white font-extrabold shadow-lg shadow-cyan-950/20' : 'border-slate-800 bg-slate-950/60 text-slate-300 hover:border-cyan-500/30' }}">
                                                {{ $slot->start_time }} - {{ $slot->end_time }}
                                            </button>
                                        @else
                                            <button disabled
                                                class="px-4 py-3.5 rounded-xl border border-slate-900 bg-slate-950 text-slate-600 text-center text-sm font-semibold cursor-not-allowed flex items-center justify-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3.5 h-3.5 opacity-60">
                                                    <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V6A1.5 1.5 0 0 0 3 7.5v5A1.5 1.5 0 0 0 4.5 14h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 6V4.5A3.5 3.5 0 0 0 8 1Zm2.5 5H5.5V4.5a2.5 2.5 0 0 1 5 0V6Z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $slot->start_time }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                                @error('timeSlotId')
                                    <span class="text-xs text-rose-500 mt-2 block">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <!-- Next button -->
                    <div class="mt-8 flex justify-end">
                        <button wire:click="goToStep(4)" @if(!$timeSlotId || !$date) disabled @endif
                            class="px-6 py-3.5 text-sm font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                            Proceed to Confirmation
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 4: Patient Info & Confirmation -->
            @if ($step === 4)
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Patient Info & Confirmation</h2>
                        <button wire:click="goToStep(3)" class="text-xs font-semibold text-slate-500 hover:text-slate-300 flex items-center gap-1">&larr; Back to slots</button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Forms: Auth or Metrics -->
                        <div class="lg:col-span-2 space-y-6">
                            @if (!Auth::check())
                                <!-- Inline Auth Form -->
                                <div class="bg-slate-950/60 p-6 rounded-2xl border border-slate-800">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="text-lg font-bold text-white">{{ $isLogin ? 'Sign In to Your Account' : 'Register New Patient Profile' }}</h3>
                                        <button type="button" wire:click="$toggle('isLogin')" class="text-xs font-semibold text-cyan-400 hover:text-cyan-300">
                                            {{ $isLogin ? 'Create an account instead' : 'I already have an account' }}
                                        </button>
                                    </div>

                                    @if ($isLogin)
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                                                <input wire:model="loginEmail" type="email" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="jane@example.com">
                                                @error('loginEmail') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                                                <input wire:model="loginPassword" type="password" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="••••••••">
                                            </div>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</label>
                                                <input wire:model="registerName" type="text" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="Jane Doe">
                                                @error('registerName') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                                                <input wire:model="registerEmail" type="email" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="jane@example.com">
                                                @error('registerEmail') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                                                <input wire:model="registerPassword" type="password" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="At least 8 characters">
                                                @error('registerPassword') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date of Birth</label>
                                                <input wire:model="dob" type="date" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 focus:outline-none focus:border-cyan-500/60 transition-colors">
                                                @error('dob') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Weight (kg)</label>
                                                <input wire:model="weight" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="60">
                                                @error('weight') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Height (cm)</label>
                                                <input wire:model="height" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="170">
                                                @error('height') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Patient Medical Metrics Form -->
                                <div class="bg-slate-950/60 p-6 rounded-2xl border border-slate-800 space-y-4">
                                    <h3 class="text-lg font-bold text-white mb-2">Patient Details</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date of Birth</label>
                                            <input wire:model="dob" type="date" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 focus:outline-none focus:border-cyan-500/60 transition-colors">
                                            @error('dob') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Weight (kg)</label>
                                            <input wire:model="weight" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="60">
                                            @error('weight') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Height (cm)</label>
                                            <input wire:model="height" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-100 placeholder-slate-600 focus:outline-none focus:border-cyan-500/60 transition-colors" placeholder="170">
                                            @error('height') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Onsite / Online Payment Selection -->
                            @if ($this->selectedService && !$this->selectedService->required_prepayment)
                                <div class="bg-slate-950/60 p-6 rounded-2xl border border-slate-800">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Payment Option</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <button type="button" wire:click="$set('paymentType', 'onsite')"
                                            class="p-4 rounded-xl border text-center text-sm font-semibold transition-all
                                            {{ $paymentType === 'onsite' ? 'border-cyan-400 bg-cyan-500/5 text-white' : 'border-slate-800 bg-slate-900 text-slate-400 hover:border-slate-700' }}">
                                            Pay Onsite
                                        </button>
                                        <button type="button" wire:click="$set('paymentType', 'online')"
                                            class="p-4 rounded-xl border text-center text-sm font-semibold transition-all
                                            {{ $paymentType === 'online' ? 'border-cyan-400 bg-cyan-500/5 text-white' : 'border-slate-800 bg-slate-900 text-slate-400 hover:border-slate-700' }}">
                                            Pay Online Now
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right Column: Glassmorphic Receipt -->
                        <div class="lg:col-span-1">
                            <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/5 to-violet-500/5 pointer-events-none"></div>
                                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-6">Booking Summary</h3>

                                <div class="space-y-4 text-sm">
                                    <div>
                                        <span class="text-xs text-slate-500 block">Treatment Service</span>
                                        <span class="font-bold text-white">{{ $this->selectedService?->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-500 block">Specialist Doctor</span>
                                        <span class="font-bold text-white">{{ $this->selectedDoctor?->user->name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-500 block">Scheduled Time Slot</span>
                                        <span class="font-bold text-white">{{ Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                                        <span class="text-xs text-cyan-400 block mt-0.5">{{ $this->selectedTimeSlot?->start_time }} - {{ $this->selectedTimeSlot?->end_time }}</span>
                                    </div>
                                    <div class="pt-4 border-t border-slate-800/80 flex justify-between items-center text-lg">
                                        <span class="font-bold text-white">Total Cost</span>
                                        <span class="font-black text-cyan-400 font-['Outfit']">THB {{ number_format($this->selectedService?->price ?? 0, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    @if ($paymentType === 'online')
                                        <button wire:click="goToStep(5)"
                                            class="w-full py-3.5 px-4 font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 transition-all">
                                            Proceed to Online Payment
                                        </button>
                                    @else
                                        <button wire:click="submitBooking"
                                            class="w-full py-3.5 px-4 font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 transition-all flex items-center justify-center gap-2">
                                            <span wire:loading.remove wire:target="submitBooking">Confirm Booking</span>
                                            <span wire:loading wire:target="submitBooking" class="flex items-center gap-2">
                                                <svg class="animate-spin h-5 w-5 text-slate-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Confirming...
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- STEP 5: Payment (Conditional) -->
            @if ($step === 5)
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Submit Prepayment</h2>
                        <button wire:click="goToStep(4)" class="text-xs font-semibold text-slate-500 hover:text-slate-300 flex items-center gap-1">&larr; Back to info</button>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Payment details and upload -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-slate-950/60 p-6 rounded-2xl border border-slate-800 space-y-6">
                                <!-- Method Selection -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Select Payment Method</label>
                                    <select wire:model="paymentMethod" class="w-full px-4 py-3.5 rounded-xl bg-slate-905 border border-slate-800 text-slate-200 focus:outline-none focus:border-cyan-500/60 transition-colors">
                                        <option value="card">Credit / Debit Card</option>
                                        <option value="qr">PromptPay QR Scan</option>
                                    </select>
                                </div>

                                <!-- Transaction QR Mock / Details -->
                                <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800 text-center space-y-4">
                                    <div class="text-xs text-slate-500 uppercase tracking-widest">CareNest Bank Account</div>
                                    <div class="text-xl font-bold text-white font-mono">SCB Bank: 123-4-56789-0</div>
                                    <div class="text-xs text-slate-500 uppercase tracking-widest pt-2">Or Scan PromptPay QR</div>
                                    <!-- Simple mock QR block representing antigravity styles -->
                                    <div class="mx-auto w-36 h-36 bg-white p-2 rounded-xl flex items-center justify-center shadow-lg relative group">
                                        <!-- Mock QR lines represented beautifully -->
                                        <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/10 to-violet-500/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl"></div>
                                        <div class="grid grid-cols-5 grid-rows-5 gap-2 w-full h-full text-slate-900">
                                            @for ($i = 0; $i < 25; $i++)
                                                <div class="rounded-sm {{ rand(0,1) ? 'bg-slate-900' : 'bg-slate-100' }}"></div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <!-- Drag-and-drop file upload zone -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Upload Transaction Screenshot</label>
                                    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-slate-800 border-dashed rounded-2xl hover:border-cyan-500/40 transition-colors relative">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-600" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-slate-400">
                                                <label for="screenshot-upload" class="relative cursor-pointer rounded-md font-semibold text-cyan-400 hover:text-cyan-300 focus-within:outline-none">
                                                    <span>Upload a file</span>
                                                    <input id="screenshot-upload" wire:model="screenshot" type="file" class="sr-only" required accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-slate-500">PNG, JPG, GIF up to 5MB</p>
                                        </div>
                                    </div>
                                    @error('screenshot') <span class="text-xs text-rose-500 mt-2 block">{{ $message }}</span> @enderror

                                    @if ($screenshot && !$errors->has('screenshot'))
                                        <div class="mt-4 p-4 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-between">
                                            <span class="text-xs text-slate-400">Screenshot uploaded: <strong class="text-slate-200">{{ $screenshot->getClientOriginalName() }}</strong></span>
                                            <button type="button" wire:click="$set('screenshot', null)" class="text-xs text-rose-400 hover:text-rose-300">Remove</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Glassmorphic Receipt -->
                        <div class="lg:col-span-1">
                            <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/5 to-violet-500/5 pointer-events-none"></div>
                                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-6">Receipt Details</h3>

                                <div class="space-y-4 text-sm mb-8">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Treatment</span>
                                        <span class="font-bold text-white text-right">{{ $this->selectedService?->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Specialist</span>
                                        <span class="font-bold text-white text-right">{{ $this->selectedDoctor?->user->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500">Time Slot</span>
                                        <span class="font-bold text-white text-right">{{ $this->selectedTimeSlot?->start_time }}</span>
                                    </div>
                                    <div class="pt-4 border-t border-slate-800/80 flex justify-between items-center text-lg">
                                        <span class="font-bold text-white">Amount Due</span>
                                        <span class="font-black text-cyan-400 font-['Outfit']">THB {{ number_format($this->selectedService?->price ?? 0, 2) }}</span>
                                    </div>
                                </div>

                                <button wire:click="submitBooking" @if(!$screenshot) disabled @endif
                                    class="w-full py-3.5 px-4 font-bold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 disabled:opacity-30 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                                    <span wire:loading.remove wire:target="submitBooking">Complete Booking</span>
                                    <span wire:loading wire:target="submitBooking" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-slate-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Finalizing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
