<!-- User Profile Dropdown -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="flex items-center space-x-3 p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-medium text-white">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="hidden md:block text-left">
            <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Administrator' }}</p>
        </div>
        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
        
        <a href="" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="fas fa-user w-4 mr-3 text-gray-400"></i>
            Profile
        </a>
        <a href="" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="fas fa-cog w-4 mr-3 text-gray-400"></i>
            Settings
        </a>
        <hr class="my-2 border-gray-200">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 w-full">
                <i class="fas fa-sign-out-alt w-4 mr-3 text-red-500"></i>
                Logout
            </button>
        </form>
    </div>
</div>