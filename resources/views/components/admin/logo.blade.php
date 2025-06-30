<!-- Logo -->
<div class="flex items-center justify-center h-16 px-4 border-b border-slate-700">
    <div class="flex items-center space-x-3">
        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
            <i class="fas fa-bolt text-white text-sm"></i>
        </div>
        <span x-show="sidebarOpen" 
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 transform scale-90"
              x-transition:enter-end="opacity-100 transform scale-100"
              x-transition:leave="transition ease-in duration-150"
              x-transition:leave-start="opacity-100 transform scale-100"
              x-transition:leave-end="opacity-0 transform scale-90"
              class="text-xl font-bold">{{ config('app.name', 'Admin') }}</span>
    </div>
</div>