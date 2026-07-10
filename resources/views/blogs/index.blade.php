<x-layouts.app>
    <!-- Background Gradient Glows -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[6000ms]"></div>
    <div class="absolute top-1/3 right-1/4 w-[600px] h-[600px] bg-violet-600/10 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[8000ms]"></div>

    <!-- Blog Index Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <!-- Hero Header -->
        <div class="text-center mb-16 relative">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/80 border border-slate-800 text-xs font-semibold text-cyan-400 mb-6 backdrop-blur-sm shadow-xl shadow-cyan-950/20">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                </span>
                CareNest Wellness Journal
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight font-['Outfit']">
                Insights From Our <br class="hidden sm:inline" />
                <span class="bg-gradient-to-r from-cyan-400 via-violet-400 to-indigo-500 bg-clip-text text-transparent drop-shadow-md">Medical Specialists</span>
            </h1>
            <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-400 leading-relaxed font-light">
                Stay updated with the latest clinical announcements, health guides, and expert wellness advice curated directly by our certified staff.
            </p>
        </div>

        @if($blogs->isEmpty())
            <!-- Empty State -->
            <div class="rounded-3xl p-12 text-center bg-slate-900/40 backdrop-blur-md border border-slate-800/80 shadow-2xl max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h15m-15-3h15m-15-3h15m-15-3h15m-15 12h15m-15-3h15" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2 font-['Outfit']">No Articles Published Yet</h3>
                <p class="text-sm text-slate-400 mb-6 font-light">Our medical practitioners are currently working on writing the latest insights. Please check back soon!</p>
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md shadow-cyan-500/10">
                    Return Home
                </a>
            </div>
        @else
            <!-- Grid of Blogs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                    <article class="flex flex-col h-full rounded-3xl bg-slate-900/30 border border-slate-900 hover:border-slate-800 hover:bg-slate-900/40 shadow-2xl transition-all duration-300 overflow-hidden group">
                        <!-- Cover Image / Gradient Placeholder -->
                        <a href="{{ route('blogs.show', $blog->slug) }}" class="block overflow-hidden relative aspect-video">
                            @if($blog->image)
                                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-900 to-slate-950 flex items-center justify-center relative">
                                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/10 to-violet-600/10 opacity-60"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-700">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h15m-15-3h15m-15-3h15m-15-3h15m-15 12h15m-15-3h15" />
                                    </svg>
                                </div>
                            @endif
                            <!-- Overlay tint -->
                            <div class="absolute inset-0 bg-slate-950/20 group-hover:opacity-0 transition-opacity duration-300"></div>
                        </a>

                        <!-- Card Body -->
                        <div class="flex flex-col flex-grow p-6 sm:p-8">
                            <!-- Category/Meta -->
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-2.5 py-0.5 rounded-lg bg-cyan-500/10 text-[10px] font-bold text-cyan-400 uppercase tracking-wider">Health Insights</span>
                                <span class="text-xs text-slate-500">&bull;</span>
                                <time class="text-xs text-slate-400" datetime="{{ $blog->created_at->toIso8601String() }}">
                                    {{ $blog->created_at->format('M d, Y') }}
                                </time>
                            </div>

                            <!-- Title -->
                            <h2 class="text-xl font-bold text-slate-100 hover:text-white leading-snug mb-3 font-['Outfit'] transition-colors line-clamp-2">
                                <a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </h2>

                            <!-- Excerpt -->
                            <p class="text-sm text-slate-400 font-light leading-relaxed mb-6 line-clamp-3">
                                {{ Str::limit(strip_tags($blog->description), 120) }}
                            </p>

                            <!-- Author & Action -->
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-900/60">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-cyan-400 to-violet-600 flex items-center justify-center text-slate-900 font-extrabold text-xs shadow-md">
                                        {{ substr($blog->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-semibold text-slate-300">{{ $blog->user->name }}</span>
                                </div>

                                <a href="{{ route('blogs.show', $blog->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold text-cyan-400 hover:text-cyan-300 transition-colors group/btn">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Custom Premium Styled Pagination -->
            <div class="mt-16 bg-slate-950 p-4 rounded-2xl border border-slate-900/60 shadow-xl">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
