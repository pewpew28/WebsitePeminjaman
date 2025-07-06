<x-admin-layout title="Collector - {{ $collector->name }}">
    <div class="container mx-auto px-4 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Total Tasks</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $statistics['total_tasks'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Active Tasks</h3>
                <p class="text-3xl font-bold text-green-600">{{ $statistics['active_tasks'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Completed Tasks</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $statistics['completed_tasks'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Success Rate</h3>
                <p class="text-3xl font-bold text-indigo-600">
                    {{ $statistics['total_tasks'] > 0 ? round(($statistics['completed_tasks'] / $statistics['total_tasks']) * 100, 1) : 0 }}%
                </p>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Tasks List</h2>
                    <div class="flex items-center space-x-4">
                        <!-- Search Filter -->
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search tasks..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <select id="statusFilter"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="tasksTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable"
                                    data-column="nasabah">
                                    <div class="flex items-center">
                                        Nasabah
                                        <svg class="ml-1 h-4 w-4 sort-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable"
                                    data-column="amount">
                                    <div class="flex items-center">
                                        Amount
                                        <svg class="ml-1 h-4 w-4 sort-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable"
                                    data-column="assigned_date">
                                    <div class="flex items-center">
                                        Assigned Date
                                        <svg class="ml-1 h-4 w-4 sort-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable"
                                    data-column="due_date">
                                    <div class="flex items-center">
                                        Due Date
                                        <svg class="ml-1 h-4 w-4 sort-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable"
                                    data-column="status">
                                    <div class="flex items-center">
                                        Status
                                        <svg class="ml-1 h-4 w-4 sort-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tasksBody">
                            @forelse($collector->collectorTasks as $task)
                                <tr class="hover:bg-gray-50"
                                    data-search-text="{{ strtolower($task->nasabah->name . ' ' . $task->installment->loan_id . ' ' . $task->status) }}"
                                    data-nasabah="{{ $task->nasabah->name }}"
                                    data-amount="{{ $task->installment->remaining_amount ?: $task->installment->total_due_amount }}"
                                    data-assigned-date="{{ $task->assigned_date->format('Y-m-d H:i:s') }}"
                                    data-due-date="{{ $task->due_date->format('Y-m-d H:i:s') }}"
                                    data-status="{{ $task->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $task->nasabah->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $task->nasabah->phone_number ?? 'No phone' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp
                                            {{ number_format($task->installment->remaining_amount ?: $task->installment->total_due_amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->assigned_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $task->assigned_date->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $task->due_date->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $task->due_date->format('H:i') }}</div>
                                        @if ($task->due_date->isPast() && !in_array($task->status, ['completed', 'paid']))
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'in_progress' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'paid' => 'bg-purple-100 text-purple-800',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No tasks found for this collector.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination info -->
                @if ($collector->collectorTasks->count() > 0)
                    <div class="mt-4 text-sm text-gray-700">
                        Showing {{ $collector->collectorTasks->count() }} tasks
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for sorting and filtering -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('tasksTable');
            const tbody = document.getElementById('tasksBody');
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            let currentSort = {
                column: null,
                direction: 'asc'
            };

            // Sorting functionality
            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.getAttribute('data-column');
                    const direction = currentSort.column === column && currentSort.direction ===
                        'asc' ? 'desc' : 'asc';

                    // Update sort icons
                    document.querySelectorAll('.sort-icon').forEach(icon => {
                        icon.classList.remove('text-blue-500', 'rotate-180');
                        icon.classList.add('text-gray-400');
                    });

                    const icon = this.querySelector('.sort-icon');
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-blue-500');
                    if (direction === 'desc') {
                        icon.classList.add('rotate-180');
                    }

                    currentSort = {
                        column,
                        direction
                    };
                    sortTable(column, direction);
                });
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                filterTable();
            });

            // Status filter functionality
            statusFilter.addEventListener('change', function() {
                filterTable();
            });

            function sortTable(column, direction) {
                const rows = Array.from(tbody.querySelectorAll('tr'));

                rows.sort((a, b) => {
                    let aValue, bValue;

                    switch (column) {
                        case 'nasabah':
                            aValue = a.getAttribute('data-nasabah') || '';
                            bValue = b.getAttribute('data-nasabah') || '';
                            break;
                        case 'amount':
                            aValue = parseFloat(a.getAttribute('data-amount')) || 0;
                            bValue = parseFloat(b.getAttribute('data-amount')) || 0;
                            break;
                        case 'assigned_date':
                            aValue = new Date(a.getAttribute('data-assigned-date'));
                            bValue = new Date(b.getAttribute('data-assigned-date'));
                            break;
                        case 'due_date':
                            aValue = new Date(a.getAttribute('data-due-date'));
                            bValue = new Date(b.getAttribute('data-due-date'));
                            break;
                        case 'status':
                            aValue = a.getAttribute('data-status') || '';
                            bValue = b.getAttribute('data-status') || '';
                            break;
                        default:
                            aValue = a.getAttribute('data-nasabah') || '';
                            bValue = b.getAttribute('data-nasabah') || '';
                    }

                    if (aValue < bValue) return direction === 'asc' ? -1 : 1;
                    if (aValue > bValue) return direction === 'asc' ? 1 : -1;
                    return 0;
                });

                // Clear tbody and append sorted rows
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            }

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();
                const rows = tbody.querySelectorAll('tr');

                rows.forEach(row => {
                    const searchText = row.getAttribute('data-search-text') || '';
                    const statusText = row.getAttribute('data-status') || '';

                    const matchesSearch = searchText.includes(searchTerm);
                    const matchesStatus = !statusValue || statusText.includes(statusValue);

                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <style>
        .sort-icon {
            transition: all 0.2s ease;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .sortable:hover {
            background-color: #f9fafb;
        }
    </style>
</x-admin-layout>