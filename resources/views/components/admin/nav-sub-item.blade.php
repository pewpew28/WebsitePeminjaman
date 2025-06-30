@props(['href'])

<a href="{{ $href }}" 
   class="flex items-center px-3 py-2 text-sm text-slate-400 rounded-lg hover:bg-slate-700/30 hover:text-white transition-colors duration-200">
    <i class="fas fa-circle text-xs w-4 text-center"></i>
    <span class="ml-3">{{ $slot }}</span>
</a>