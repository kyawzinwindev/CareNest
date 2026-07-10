<x-layouts.app>
    <!-- Background Gradient Glows -->
    <div class="absolute top-0 left-1/3 w-[600px] h-[600px] bg-cyan-500/5 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[6000ms]"></div>
    <div class="absolute top-1/2 right-1/4 w-[500px] h-[500px] bg-violet-600/5 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse duration-[8000ms]"></div>

    <!-- Custom Rich Text Typography Styles -->
    <style>
        .blog-content h1 {
            font-size: 1.875rem; /* 30px */
            line-height: 2.25rem;
            font-weight: 700;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .blog-content h2 {
            font-size: 1.5rem; /* 24px */
            line-height: 2rem;
            font-weight: 700;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .blog-content h3 {
            font-size: 1.25rem; /* 20px */
            line-height: 1.75rem;
            font-weight: 600;
            color: #f1f5f9;
            font-family: 'Outfit', sans-serif;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .blog-content p {
            font-size: 1rem; /* 16px */
            line-height: 1.8;
            color: #cbd5e1;
            font-weight: 300;
            margin-bottom: 1.5rem;
        }
        .blog-content ul {
            list-style-type: disc;
            list-style-position: inside;
            margin-bottom: 1.5rem;
            padding-left: 0.5rem;
        }
        .blog-content ul li {
            font-size: 1rem;
            line-height: 1.8;
            color: #cbd5e1;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }
        .blog-content ol {
            list-style-type: decimal;
            list-style-position: inside;
            margin-bottom: 1.5rem;
            padding-left: 0.5rem;
        }
        .blog-content ol li {
            font-size: 1rem;
            line-height: 1.8;
            color: #cbd5e1;
            font-weight: 300;
            margin-bottom: 0.5rem;
        }
        .blog-content a {
            color: #22d3ee; /* cyan-400 */
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
        }
        .blog-content a:hover {
            color: #67e8f9; /* cyan-300 */
        }
        .blog-content blockquote {
            border-left: 4px solid #06b6d4; /* cyan-500 */
            background-color: rgba(15, 23, 42, 0.6);
            padding: 1.5rem 2rem;
            border-radius: 0 1rem 1rem 0;
            margin: 2rem 0;
            font-style: italic;
            color: #e2e8f0;
        }
        .blog-content img {
            border-radius: 1.5rem;
            border: 1px solid #1e293b;
            margin: 2rem auto;
            max-width: 100%;
            height: auto;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.5);
            display: block;
        }
    </style>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('blogs.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                Back to Blogs
            </a>
        </div>

        <!-- Blog Header -->
        <header class="mb-10 text-center sm:text-left">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mb-6">
                <span class="px-2.5 py-0.5 rounded-lg bg-cyan-500/10 text-[10px] font-bold text-cyan-400 uppercase tracking-wider">Health Insights</span>
                <span class="text-slate-700 hidden sm:inline">&bull;</span>
                <time class="text-xs text-slate-400" datetime="{{ $blog->created_at->toIso8601String() }}">
                    Published on {{ $blog->created_at->format('F d, Y') }}
                </time>
            </div>

            <h1 class="text-3xl sm:text-5xl font-extrabold text-white leading-tight font-['Outfit'] mb-6">
                {{ $blog->title }}
            </h1>

            <!-- Author & Metadata Card -->
            <div class="inline-flex items-center gap-3 p-2 pr-4 rounded-2xl bg-slate-900/40 border border-slate-800/80 backdrop-blur-md">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-400 to-violet-600 flex items-center justify-center text-slate-900 font-extrabold text-sm shadow-md">
                    {{ substr($blog->user->name, 0, 1) }}
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-slate-200 leading-none">{{ $blog->user->name }}</p>
                    <p class="text-[10px] text-slate-500 font-medium mt-0.5">CareNest Contributor</p>
                </div>
            </div>
        </header>

        <!-- Cover Image -->
        <div class="mb-12 rounded-3xl border border-slate-900 overflow-hidden shadow-2xl aspect-[21/9]">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="object-cover w-full h-full">
            @else
                <div class="w-full h-full bg-gradient-to-br from-slate-900 to-slate-950 flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/10 to-violet-600/10 opacity-60"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-800">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h15m-15-3h15m-15-3h15m-15-3h15m-15 12h15m-15-3h15" />
                    </svg>
                </div>
            @endif
        </div>

        <!-- Blog Body -->
        <div class="bg-slate-900/20 border border-slate-900/60 rounded-3xl p-6 sm:p-10 shadow-2xl relative">
            <div class="blog-content">
                {!! $blog->description !!}
            </div>

            <!-- Footer Booking CTA -->
            <div class="mt-16 pt-8 border-t border-slate-900/60 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="text-lg font-bold text-white font-['Outfit']">Need Medical Guidance?</h4>
                    <p class="text-sm text-slate-400 font-light mt-1">Book an appointment online with one of our specialists today.</p>
                </div>
                <a href="{{ url('/booking') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-sm font-semibold rounded-xl text-slate-900 bg-gradient-to-r from-cyan-400 to-violet-500 hover:from-cyan-300 hover:to-violet-400 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md shadow-cyan-500/10">
                    Book Appointment
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
