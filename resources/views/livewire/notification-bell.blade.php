<div x-data="{ open: false }" class="relative">
    <!-- Bell Button -->
    <button @click="open = !open" @click.away="open = false" class="relative p-2 text-slate-400 hover:text-white transition-all rounded-xl hover:bg-slate-900 border border-transparent hover:border-slate-800 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-gradient-to-tr from-rose-500 to-red-600 text-[10px] font-black text-white shadow-lg shadow-rose-500/30">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-slate-950/90 backdrop-blur-lg border border-slate-800/80 shadow-2xl p-2 z-50 overflow-hidden"
         style="display: none;">
        
        <div class="flex items-center justify-between px-3 py-2 border-b border-slate-800/60 mb-2">
            <span class="text-xs font-bold text-white uppercase tracking-wider">Notifications</span>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[11px] text-cyan-400 hover:text-cyan-300 transition-colors font-semibold">
                    Mark all as read
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto space-y-1.5 pr-1">
            @forelse($notifications as $notification)
                <div wire:key="noti-{{ $notification['id'] }}" 
                     wire:click="markAsRead({{ $notification['id'] }})"
                     class="flex flex-col p-3 rounded-xl cursor-pointer transition-all duration-200 {{ $notification['is_read'] ? 'opacity-40 hover:opacity-70 hover:bg-slate-900/50' : 'bg-slate-900/60 border border-slate-800/80 hover:bg-slate-800/60 hover:border-slate-700' }}">
                    
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold {{ $notification['is_read'] ? 'text-slate-400' : 'bg-gradient-to-r from-cyan-400 to-teal-300 bg-clip-text text-transparent' }}">
                            {{ $notification['title'] }}
                        </span>
                        <span class="text-[10px] text-slate-500 font-medium">
                            {{ $notification['created_at_human'] }}
                        </span>
                    </div>
                    
                    <p class="text-xs text-slate-300 leading-normal font-medium">
                        {{ $notification['message'] }}
                    </p>
                </div>
            @empty
                <div class="py-8 text-center text-slate-500 text-xs font-medium">
                    No notifications yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
