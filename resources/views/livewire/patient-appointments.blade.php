<div class="relative min-h-[85vh] py-12 px-4 sm:px-6 lg:px-8">
    <!-- Glow effects -->
    <div class="absolute top-10 left-1/4 w-[400px] h-[400px] bg-cyan-500/10 rounded-full blur-3xl -z-10 pointer-events-none"></div>

    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-['Outfit']">Appointment History</h1>
                <p class="text-sm text-slate-400 font-light mt-1">Track and manage your upcoming and past medical consultations</p>
            </div>
            <a href="{{ url('/booking') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 transition-all duration-200">
                Book New Appointment
            </a>
        </div>

        <!-- Session Message Displays -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm font-medium">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card List -->
        @if ($this->appointments->isEmpty())
            <div class="p-12 border border-dashed border-slate-800 bg-slate-900/20 backdrop-blur-md rounded-3xl text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-600 mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                <h3 class="text-lg font-bold text-white mb-2">No Bookings Yet</h3>
                <p class="text-sm text-slate-500 font-light max-w-sm mx-auto mb-6">You haven't scheduled any appointments yet. Click below to secure your first clinical slot.</p>
                <a href="{{ url('/booking') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold rounded-xl text-slate-900 bg-cyan-400 hover:bg-cyan-300 transition-colors">
                    Schedule Appointment
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($this->appointments as $appointment)
                    <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:border-slate-700 transition-colors">

                        <!-- Left Details -->
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-semibold text-slate-500 uppercase">Appt #{{ $appointment->id }}</span>
                                <span class="text-xs font-bold px-2 py-0.5 rounded-full  tracking-wider
                                    {{ $appointment->status === App\Enums\AppointmentStatus::CONFIRMED ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' :
                                       ($appointment->status === App\Enums\AppointmentStatus::PENDING ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' :
                                        'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                    {{ $appointment->status->label() }}
                                </span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-950 text-slate-400 border border-slate-800">
                                    {{ $appointment->payment_type->label() }}
                                </span>
                            </div>

                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-white">{{ $appointment->service->name }}</h3>
                                <div class="flex items-center gap-2 text-sm text-slate-400">
                                    <span>Specialist: <strong>{{ $appointment->doctor->user->name }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time Slot Details -->
                        <div class="bg-slate-950/60 border border-slate-850 px-4 py-3 rounded-xl min-w-[200px] text-center md:text-left">
                            <span class="text-xs text-slate-500 block">Scheduled Date</span>
                            <span class="font-bold text-white block text-sm">{{ Carbon\Carbon::parse($appointment->time_slot->schedule->date)->format('M d, Y') }}</span>
                            <span class="text-xs text-cyan-400 block mt-0.5">{{ $appointment->time_slot->start_time }} - {{ $appointment->time_slot->end_time }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="w-full md:w-auto flex justify-end shrink-0">
                            @if ($appointment->status === App\Enums\AppointmentStatus::PENDING)
                                <button wire:click="cancelAppointment({{ $appointment->id }})"
                                    wire:confirm="Are you sure you want to cancel this appointment?"
                                    class="text-xs font-semibold text-rose-400 hover:text-rose-350 hover:bg-rose-500/10 px-3.5 py-2 rounded-xl border border-rose-500/20 hover:border-rose-500/30 transition-all w-full md:w-auto text-center">
                                    Cancel Booking
                                </button>
                            @else
                                <span class="text-xs text-slate-600 italic">No actions available</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
