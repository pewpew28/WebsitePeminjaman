<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Collector: {{ $collector->name }}</h1>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
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

        <!-- Tasks Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Tasks List</h2>
                    <a href="{{ route('admin.collector-tasks.assign', $collector) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Assign New Task</a>
                </div>

                <!-- Filters for AJAX -->
                <div class="mb-4 flex gap-4">
                    <select id="collectorFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">All Collectors</option>
                        @foreach($collectors as $c)
                            <option value="{{ $c->id }}" {{ $c->id == $collector->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <select id="statusFilter" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button id="filterTasks" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="tasksTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nasabah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="tasksBody">
                            @foreach($collector->collectorTasks as $task)
                                <tr>
                                    <td class="px-6 py-4">{{ $task->nasabah->name }}</td>
                                    <td class="px-6 py-4">{{ $task->loan_id ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $task->assigned_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $task->due_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.collector-tasks.update-status', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="active" {{ $task->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.collector-tasks.edit', $task) }}" class="text-blue-500 hover:text-blue-700 mr-2">Edit</a>
                                        <form action="{{ route('admin.collector-tasks.destroy', $task) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tasks.js') }}"></script>
</x-admin-layout>