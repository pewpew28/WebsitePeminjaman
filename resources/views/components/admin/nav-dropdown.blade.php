@props(['icon', 'title', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button @click="open = !open" 
            class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium {{ $active ? 'text-blue-300 bg-blue-600/20' : 'text-slate-300' }} rounded-lg hover:bg-slate-700/50 hover:text-white transition-colors duration-200 group">
        <div class="flex items-center">
            <i class="{{ $icon }} w-5 text-center"></i>
            <span x-show="sidebarOpen" 
                  x-transition:enter="transition ease-out duration-200 delay-100"
                  x-transition:enter-start="opacity-0 transform translate-x-2"
                  x-transition:enter-end="opacity-100 transform translate-x-0"
                  class="ml-3">{{ $title }}</span>
        </div>
        <i x-show="sidebarOpen" 
           :class="open ? 'fa-chevron-down' : 'fa-chevron-right'" 
           class="fas text-xs transition-transform duration-200"></i>
    </button>
    
    <!-- Submenu -->
    <div x-show="open && sidebarOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-1"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-6 mt-2 space-y-1">
        {{ $slot }}
    </div>
</div>