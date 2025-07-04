<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Collector Tasks Dashboard</h1>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Total Collectors</h3>
                <p class="text-2xl font-bold">{{ $statistics['total_collectors'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Total Tasks</h3>
                <p class="text-2xl font-bold">{{ $statistics['total_tasks'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Active Tasks</h3>
                <p class="text-2xl font-bold">{{ $statistics['active_tasks'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Completed Tasks</h3>
                <p class="text-2xl font-bold">{{ $statistics['completed_tasks'] }}</p>
            </div>
        </div>

        <!-- Collectors Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Collectors List</h2>
                    <a href="{{ route('admin.collector-tasks.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Task</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Collector</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active Tasks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($collectors as $collector)
                                <tr>
                                    <td class="px-6 py-4">{{ $collector->name }}</td>
                                    <td class="px-6 py-4">{{ $collector->collectorTasks->count() }}</td>
                                    <td class="px-6 py-4">{{ $collector->collectorTasks->where('status', 'active')->count() }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.collector-tasks.show', $collector) }}" class="text-blue-500 hover:text-blue-700 mr-2">View</a>
                                        <a href="{{ route('admin.collector-tasks.assign', $collector) }}" class="text-green-500 hover:text-green-700">Assign Task</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>