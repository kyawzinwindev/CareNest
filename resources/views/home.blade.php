<x-layouts.app>
    <!-- Background Gradient Glows (Antigravity Theme) -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[6000ms]"></div>
    <div class="absolute top-1/3 right-1/4 w-[600px] h-[600px] bg-violet-600/10 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[8000ms]"></div>

    <!-- Hero Section -->
    <section class="relative min-h-[85vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20 overflow-hidden">
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <!-- Glowing Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/80 border border-slate-800 text-xs font-semibold text-cyan-400 mb-8 backdrop-blur-sm shadow-xl shadow-cyan-950/20">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                </span>
                Next-Gen Healthcare Management
            </div>

            <!-- Main Headline -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white mb-6 leading-[1.1] font-['Outfit']">
                Experience Clinic Care <br class="hidden sm:inline" />
                <span class="bg-gradient-to-r from-cyan-400 via-violet-400 to-indigo-500 bg-clip-text text-transparent drop-shadow-md">Without Friction</span>
            </h1>

            <!-- Subtext -->
            <p class="max-w-2xl mx-auto text-lg sm:text-xl text-slate-400 mb-10 leading-relaxed font-light">
                CareNest blends state-of-the-art medical practitioners with real-time concurrency-protected scheduling. Book instantly, rest assured.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/booking') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-2xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 hover:scale-105 active:scale-95 transition-all duration-200 shadow-xl shadow-cyan-500/10">
                    Book an Appointment Now
                </a>
                <a href="#specialties" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-2xl text-slate-200 bg-slate-900/60 border border-slate-800/80 hover:bg-slate-850 hover:text-white hover:border-slate-700 hover:scale-105 active:scale-95 transition-all duration-200 backdrop-blur-sm">
                    Explore Services
                </a>
            </div>
        </div>

        <!-- Decorative Floating Shapes -->
        <div class="absolute bottom-10 left-10 w-24 h-24 bg-gradient-to-br from-cyan-500 to-violet-600 rounded-3xl opacity-20 blur-xl animate-bounce duration-[10000ms] hidden lg:block"></div>
        <div class="absolute top-20 right-10 w-32 h-32 bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-full opacity-10 blur-xl animate-pulse duration-[7000ms] hidden lg:block"></div>
    </section>

    <!-- Bento Grid / Asymmetric Layout (Zip-Zap Blocks) -->
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Block A: Quick Stats (Double width on md/lg) -->
            <div class="md:col-span-2 rounded-3xl p-8 bg-slate-900/40 backdrop-blur-md border border-slate-800/80 shadow-2xl flex flex-col justify-between hover:border-cyan-500/30 transition-all duration-300 group">
                <div class="mb-8">
                    <span class="text-xs font-semibold tracking-wider text-cyan-400 uppercase">CareNest Metrics</span>
                    <h3 class="text-3xl font-bold text-white mt-2 font-['Outfit']">Our Numbers Speak Voluminous Care</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    <div>
                        <div class="text-4xl sm:text-5xl font-extrabold text-white font-['Outfit'] bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">50+</div>
                        <div class="text-sm text-slate-400 mt-1">Specialized Doctors</div>
                    </div>
                    <div>
                        <div class="text-4xl sm:text-5xl font-extrabold text-white font-['Outfit'] bg-gradient-to-r from-violet-400 to-indigo-500 bg-clip-text text-transparent">99.9%</div>
                        <div class="text-sm text-slate-400 mt-1">Booking Accuracy</div>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <div class="text-4xl sm:text-5xl font-extrabold text-white font-['Outfit'] bg-gradient-to-r from-fuchsia-400 to-pink-500 bg-clip-text text-transparent">10k+</div>
                        <div class="text-sm text-slate-400 mt-1">Happy Patients</div>
                    </div>
                </div>
            </div>

            <!-- Block B: Real-time Concurrency Lock Badge -->
            <div class="rounded-3xl p-8 bg-slate-900/40 backdrop-blur-md border border-slate-800/80 shadow-2xl flex flex-col justify-between hover:border-violet-500/30 transition-all duration-300 group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 font-['Outfit']">Double Booking Shield</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Powered by pessimistic transaction locks. Once you click a slot, it is instantly shielded and reserved for you. No race conditions, no double bookings.
                    </p>
                </div>
                <div class="mt-6 flex items-center gap-2 text-xs text-violet-400 font-semibold">
                    <span>Active Protection Enabled</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>

            <!-- Block C: Interactive Specialties Bento -->
            <div class="rounded-3xl p-8 bg-slate-900/40 backdrop-blur-md border border-slate-800/80 shadow-2xl flex flex-col justify-between hover:border-pink-500/30 transition-all duration-300 group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-pink-400 mb-6 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 font-['Outfit']">Board-Certified Excellence</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        All CareNest practitioners are vetted medical staff with verified credentials, ensuring you receive elite level patient treatments.
                    </p>
                </div>
                <div class="mt-6">
                    <a href="#specialties" class="text-sm text-pink-400 hover:text-pink-300 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        Explore specialties &rarr;
                    </a>
                </div>
            </div>

            <!-- Block D: Dynamic Services Quick links (Double width on md/lg) -->
            <div class="md:col-span-2 rounded-3xl p-8 bg-slate-900/40 backdrop-blur-md border border-slate-800/80 shadow-2xl flex flex-col justify-between hover:border-cyan-500/30 transition-all duration-300 group">
                <div>
                    <span class="text-xs font-semibold tracking-wider text-cyan-400 uppercase">Quick Access</span>
                    <h3 class="text-2xl font-bold text-white mt-2 mb-6 font-['Outfit']">Popular Services by Specialization</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ url('/booking') }}" class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 hover:border-cyan-500/40 hover:bg-slate-900/40 transition-all group/link">
                            <span class="text-xs text-cyan-400 font-semibold block">Pediatrics</span>
                            <span class="text-slate-200 font-semibold text-sm mt-1 block group-hover/link:text-white">Child Growth Assessment</span>
                            <span class="text-slate-500 text-xs mt-1 block">Full development & immunization checkup</span>
                        </a>
                        <a href="{{ url('/booking') }}" class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 hover:border-violet-500/40 hover:bg-slate-900/40 transition-all group/link">
                            <span class="text-xs text-violet-400 font-semibold block">Cardiology</span>
                            <span class="text-slate-200 font-semibold text-sm mt-1 block group-hover/link:text-white">Cardiovascular Screen</span>
                            <span class="text-slate-500 text-xs mt-1 block">Advanced ECG & blood pressure profiling</span>
                        </a>
                        <a href="{{ url('/booking') }}" class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 hover:border-pink-500/40 hover:bg-slate-900/40 transition-all group/link">
                            <span class="text-xs text-pink-400 font-semibold block">Dermatology</span>
                            <span class="text-slate-200 font-semibold text-sm mt-1 block group-hover/link:text-white">Laser Skin Therapy</span>
                            <span class="text-slate-500 text-xs mt-1 block">Premium cosmetic & medical dermatology</span>
                        </a>
                        <a href="{{ url('/booking') }}" class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800/80 hover:border-emerald-500/40 hover:bg-slate-900/40 transition-all group/link">
                            <span class="text-xs text-emerald-400 font-semibold block">General Medicine</span>
                            <span class="text-slate-200 font-semibold text-sm mt-1 block group-hover/link:text-white">General Wellness Consult</span>
                            <span class="text-slate-500 text-xs mt-1 block">GP checkup and prescription refills</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Specialties Carousel / Slider Section -->
    <section id="specialties" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-white font-['Outfit']">Our Medical Specialties</h2>
            <p class="text-slate-400 font-light mt-2 max-w-xl mx-auto">Select from our specialized wings to view available treatments and book certified practitioners.</p>
        </div>

        <!-- Alpine.js Slider for Specialties -->
        <div x-data="{ 
            activeSlide: 0,
            slides: [
                { title: 'Cardiology', desc: 'Heart health, ECG, cardiovascular screens.', icon: 'heroicon-m-heart', gradient: 'from-rose-500 to-pink-600', val: 'cardiology' },
                { title: 'Dermatology', desc: 'Acne, skin screens, and custom therapies.', icon: 'heroicon-m-sparkles', gradient: 'from-amber-400 to-orange-500', val: 'dermatology' },
                { title: 'Pediatrics', desc: 'Child health checks, growth monitoring.', icon: 'heroicon-m-user-group', gradient: 'from-emerald-400 to-teal-500', val: 'pediatrics' },
                { title: 'General Medicine', desc: 'Family care, routine checkups, GP advice.', icon: 'heroicon-m-shield-check', gradient: 'from-cyan-400 to-blue-500', val: 'general_medicine' },
                { title: 'Neurology', desc: 'Nervous system, cognitive health screening.', icon: 'heroicon-m-bolt', gradient: 'from-violet-500 to-purple-600', val: 'neurology' },
                { title: 'Orthopedics', desc: 'Bone, joint, and muscular wellness therapies.', icon: 'heroicon-m-key', gradient: 'from-indigo-500 to-blue-600', val: 'orthopedics' }
            ],
            next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
            prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
        }" class="relative">
            <!-- Slider Track -->
            <div class="overflow-hidden rounded-3xl border border-slate-800/80 bg-slate-900/20 backdrop-blur-md p-6 sm:p-10 relative">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <!-- Text Info -->
                    <div class="order-2 md:order-1">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                                <div class="inline-flex items-center justify-center p-3 rounded-2xl text-white bg-gradient-to-tr" :class="slide.gradient">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-bold text-white font-['Outfit']" x-text="slide.title"></h3>
                                <p class="text-slate-400 font-light leading-relaxed" x-text="slide.desc"></p>
                                <div class="pt-4">
                                    <a :href="'{{ url('/booking') }}?specialization=' + slide.val" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold rounded-xl text-slate-900 bg-cyan-400 hover:bg-cyan-300 hover:scale-105 active:scale-95 transition-all">
                                        Book in this Specialty
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Slide Card Display -->
                    <div class="order-1 md:order-2 flex justify-center">
                        <div class="w-full max-w-sm h-64 rounded-3xl overflow-hidden relative shadow-2xl flex items-center justify-center bg-slate-950/80 border border-slate-800">
                            <!-- Glowing shape inside slide visual -->
                            <template x-for="(slide, index) in slides" :key="index">
                                <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500 transform" x-transition:enter-start="opacity-0 scale-90 rotate-6" x-transition:enter-end="opacity-100 scale-100 rotate-0" class="absolute inset-0 flex flex-col justify-between p-8">
                                    <div class="absolute inset-0 bg-gradient-to-tr opacity-20 blur-2xl" :class="slide.gradient"></div>
                                    <div class="flex justify-between items-start z-10">
                                        <span class="text-sm font-semibold text-slate-500 uppercase tracking-widest">CareNest Wing</span>
                                        <span class="text-2xl font-bold text-slate-700" x-text="'0' + (index + 1)"></span>
                                    </div>
                                    <div class="z-10">
                                        <h4 class="text-2xl font-bold text-white font-['Outfit']" x-text="slide.title"></h4>
                                        <p class="text-xs text-slate-500 mt-1">Accredited Clinical Department</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="absolute bottom-4 right-4 flex items-center gap-2">
                    <button @click="prev()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button @click="next()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Slider Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-white font-['Outfit']">What Our Patients Say</h2>
            <p class="text-slate-400 font-light mt-2 max-w-xl mx-auto">Real experiences from patients who transitioned their care to CareNest.</p>
        </div>

        <div x-data="{
            active: 0,
            testimonials: [
                { name: 'Sarah Jenkins', role: 'Cardiology Patient', quote: 'The real-time time slot selector was a breath of fresh air. I picked 10:30 AM, and when I arrived at the clinic, the doctor was ready. Exceptional scheduling!', initial: 'S' },
                { name: 'Michael Chen', role: 'Pediatrics Parent', quote: 'Booking for my daughter took less than two minutes. The interface is gorgeous, and uploading the payment screenshot worked flawlessly on mobile.', initial: 'M' },
                { name: 'Elena Rostova', role: 'Dermatology Patient', quote: 'Soft glassmorphic dashboard looks like it belongs in the future. The doctors are incredibly caring, and there were absolutely no double booking delays.', initial: 'E' }
            ],
            next() { this.active = (this.active + 1) % this.testimonials.length },
            prev() { this.active = (this.active - 1 + this.testimonials.length) % this.testimonials.length }
        }" class="max-w-3xl mx-auto relative">
            <div class="overflow-hidden rounded-3xl border border-slate-800/80 bg-slate-900/10 backdrop-blur-md p-8 sm:p-12 shadow-2xl relative">
                <template x-for="(item, index) in testimonials" :key="index">
                    <div x-show="active === index" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="space-y-6">
                        <!-- Quote Icon -->
                        <div class="text-cyan-400/20 text-6xl font-serif absolute top-4 left-6 pointer-events-none">&ldquo;</div>
                        <p class="text-lg sm:text-xl text-slate-200 leading-relaxed font-light italic relative z-10" x-text="item.quote"></p>
                        
                        <div class="flex items-center gap-4 pt-4 border-t border-slate-800/60">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-cyan-400 to-violet-600 flex items-center justify-center text-slate-900 font-extrabold text-lg" x-text="item.initial"></div>
                            <div>
                                <h4 class="font-bold text-white" x-text="item.name"></h4>
                                <span class="text-xs text-slate-500" x-text="item.role"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Navigation Controls -->
                <div class="absolute bottom-6 right-8 flex items-center gap-2">
                    <button @click="prev()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button @click="next()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
