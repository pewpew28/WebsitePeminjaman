@props(['title' => 'Dashboard'])
<!-- Header -->
<header class="fixed top-0 right-0 z-40 transition-all duration-300 ease-in-out bg-white shadow-sm border-b border-gray-200"
        :class="sidebarOpen ? 'left-64' : 'left-16'">
    <div class="flex items-center justify-between h-16 px-6">
        
        <!-- Left side -->
        <div class="flex items-center space-x-4">
            <!-- Toggle Sidebar Button -->
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="p-2 text-gray-500 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-xl font-semibold text-gray-800">{{ $title }}</h1>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            
            <!-- Search Component -->
            {{-- <x-admin.search /> --}}

            <!-- Notifications Component -->
            <x-admin.notifications />

            <!-- User Profile Dropdown Component -->
            <x-admin.user-dropdown />
        </div>
    </div>
</header>