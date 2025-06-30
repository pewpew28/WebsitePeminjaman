<!-- User Profile -->
<div class="p-3 border-t border-slate-700">
    <div class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-slate-700/50 transition-colors duration-200">
        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-sm font-medium">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 transform translate-x-2"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
        </div>
    </div>
</div>