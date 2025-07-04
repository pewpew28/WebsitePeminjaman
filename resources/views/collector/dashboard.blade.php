<x-user-layout title="Dashboard">

    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats Cards -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Tugas</p>
                    <p class="text-2xl font-bold text-gray-900">1,234</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <i class="fas fa-arrow-up text-green-500 text-sm mr-1"></i>
                <span class="text-sm text-green-500">+12%</span>
                <span class="text-sm text-gray-500 ml-1">from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Uang Yang DiKutip</p>
                    <p class="text-2xl font-bold text-gray-900">$45,678</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <i class="fas fa-arrow-up text-green-500 text-sm mr-1"></i>
                <span class="text-sm text-green-500">+8%</span>
                <span class="text-sm text-gray-500 ml-1">from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Yang Sudah DiKutip</p>
                    <p class="text-2xl font-bold text-gray-900">892</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-shopping-bag text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <i class="fas fa-arrow-down text-red-500 text-sm mr-1"></i>
                <span class="text-sm text-red-500">-3%</span>
                <span class="text-sm text-gray-500 ml-1">from last month</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pertumbuhan</p>
                    <p class="text-2xl font-bold text-gray-900">24.5%</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <i class="fas fa-arrow-up text-green-500 text-sm mr-1"></i>
                <span class="text-sm text-green-500">+15%</span>
                <span class="text-sm text-gray-500 ml-1">from last month</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">New user registered</p>
                            <p class="text-xs text-gray-500">2 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-100 p-2 rounded-full">
                            <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">New order received</p>
                            <p class="text-xs text-gray-500">5 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">System alert</p>
                            <p class="text-xs text-gray-500">10 minutes ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <button class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        Create New User
                    </button>
                    <button class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-file-export mr-2"></i>
                        Export Data
                    </button>
                    <button class="w-full bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-cog mr-2"></i>
                        System Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Scripts -->
    @push('scripts')
    <script>
        // Dashboard specific JavaScript
        console.log('Dashboard loaded');
    </script>
    @endpush
</x-user-layout>