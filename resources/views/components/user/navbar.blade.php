<!-- Navbar Component -->
<nav class="bg-white shadow-lg border-b border-gray-200 fixed top-0 left-0 right-0 z-50 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('collector.dashboard') }}" class="flex items-center">
                        <h1 class="text-2xl font-bold text-blue-600">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ env('APP_NAME') }}
                        </h1>
                    </a>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    @if (Auth::user()->role === 'collector')
                        <a href="{{ route('collector.dashboard') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('collector.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-home mr-1"></i>
                            Home
                        </a>
                    @else
                        <a href="{{ route('nasabah.dashboard') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('collector.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <i class="fas fa-home mr-1"></i>
                            Home
                        </a>
                    @endif
                    {{-- <a href="{{ route('profile.show') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('profile.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-user mr-1"></i>
                        Profile
                    </a>
                    <a href="{{ route('settings.index') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('settings.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-cog mr-1"></i>
                        Settings
                    </a>
                    <a href="{{ route('notifications.index') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('notifications.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                        <i class="fas fa-bell mr-1"></i>
                        Notifications
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a> --}}
                </div>
            </div>

            <!-- User Menu Desktop -->
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- User dropdown -->
                    <div class="relative">
                        <button id="userMenuButton"
                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:bg-gray-50 p-2 transition duration-200">
                            <img class="h-8 w-8 rounded-full border-2 border-gray-200"
                                src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3b82f6&color=fff"
                                alt="User Avatar">
                            <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-1 text-gray-500 text-xs"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="userDropdown"
                            class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden transform opacity-0 scale-95 transition-all duration-200">
                            <div class="py-1">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                {{-- <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    My Profile
                                </a>
                                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                                    <i class="fas fa-cog mr-3 text-gray-400"></i>
                                    Settings
                                </a>
                                <a href="{{ route('help.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">
                                    <i class="fas fa-question-circle mr-3 text-gray-400"></i>
                                    Help Center
                                </a> --}}
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition duration-150">
                                        <i class="fas fa-sign-out-alt mr-3 text-red-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobileMenuButton"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition duration-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @if (Auth::user()->role === 'collector')
                <a href="{{ route('collector.dashboard') }}"
                    class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('collector.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
            @else
                <a href="{{ route('nasabah.dashboard') }}"
                    class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('collector.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
            @endif

            {{-- <a href="{{ route('profile.show') }}" 
               class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('profile.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-user mr-2"></i>
                Profile
            </a>
            <a href="{{ route('settings.index') }}" 
               class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('settings.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-cog mr-2"></i>
                Settings
            </a>
            <a href="{{ route('notifications.index') }}" 
               class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-200 {{ request()->routeIs('notifications.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <i class="fas fa-bell mr-2"></i>
                Notifications
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a> --}}
        </div>

        <!-- Mobile user section -->
        <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full border-2 border-gray-200"
                        src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=3b82f6&color=fff"
                        alt="User Avatar">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                {{-- <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-user mr-2"></i>
                    My Profile
                </a>
                <a href="{{ route('settings.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-cog mr-2"></i>
                    Settings
                </a>
                <a href="{{ route('help.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100 transition duration-200">
                    <i class="fas fa-question-circle mr-2"></i>
                    Help Center
                </a> --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- JavaScript for navbar functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');

                // Change hamburger icon
                const icon = mobileMenuButton.querySelector('i');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                }
            });
        }

        // User dropdown toggle (desktop)
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');

                // Add smooth animation
                if (userDropdown.classList.contains('hidden')) {
                    userDropdown.classList.remove('opacity-100', 'scale-100');
                    userDropdown.classList.add('opacity-0', 'scale-95');
                } else {
                    userDropdown.classList.remove('opacity-0', 'scale-95');
                    userDropdown.classList.add('opacity-100', 'scale-100');
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (userMenuButton && userDropdown &&
                !userMenuButton.contains(event.target) &&
                !userDropdown.contains(event.target)) {
                userDropdown.classList.add('hidden');
                userDropdown.classList.remove('opacity-100', 'scale-100');
                userDropdown.classList.add('opacity-0', 'scale-95');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (mobileMenuButton && mobileMenu &&
                !mobileMenuButton.contains(event.target) &&
                !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                const icon = mobileMenuButton.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && mobileMenu) {
                mobileMenu.classList.add('hidden');
                const icon = mobileMenuButton?.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }
        });
    });
</script>
