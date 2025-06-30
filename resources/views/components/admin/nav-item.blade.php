@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}" 
   {{ $attributes->merge([
       'class' => $active 
           ? 'flex items-center px-3 py-3 text-sm font-medium text-blue-300 bg-blue-600/20 rounded-lg hover:bg-blue-600/30 transition-colors duration-200 group'
           : 'flex items-center px-3 py-3 text-sm font-medium text-slate-300 rounded-lg hover:bg-slate-700/50 hover:text-white transition-colors duration-200 group'
   ]) }}>
    <i class="{{ $icon }} w-5 text-center"></i>
    <span x-show="sidebarOpen" 
          x-transition:enter="transition ease-out duration-200 delay-100"
          x-transition:enter-start="opacity-0 transform translate-x-2"
          x-transition:enter-end="opacity-100 transform translate-x-0"
          class="ml-3">{{ $slot }}</span>
</a>