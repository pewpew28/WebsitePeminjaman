<x-admin-layout title="Loans">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Loan Management</h1>
                <p class="mt-2 text-sm text-gray-600">Manage and track all loan applications and approvals</p>
            </div>
            <div class="mt-4 lg:mt-0 flex flex-wrap gap-3">
                <button onclick="exportData()" 
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors duration-200 shadow-sm border border-gray-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Export
                </button>
                <a href="{{ route('admin.loans.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Loan
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Loans</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $loans->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $loans->where('status', 'pending')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $loans->where('status', 'active')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                                <dd class="text-lg font-semibold text-gray-900">Rp {{ number_format($loans->sum('loan_amount'), 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Advanced Filter Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <button onclick="toggleFilters()" class="flex items-center justify-between w-full text-left">
                    <h3 class="text-lg font-medium text-gray-900">Advanced Filters</h3>
                    <svg id="filterIcon" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div id="filterPanel" class="px-6 py-4 space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search by Name -->
                    <div class="relative">
                        <label for="nameFilter" class="block text-sm font-medium text-gray-700 mb-1">Search Nasabah</label>
                        <input type="text" 
                               id="nameFilter" 
                               placeholder="Enter nasabah name..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                               oninput="filterTable()">
                        <svg class="absolute left-3 top-8 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="statusFilter" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                onchange="filterTable()">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Amount Range -->
                    <div>
                        <label for="amountFilter" class="block text-sm font-medium text-gray-700 mb-1">Amount Range</label>
                        <select id="amountFilter" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                onchange="filterTable()">
                            <option value="">All Amounts</option>
                            <option value="0-10000000">< 10 Million</option>
                            <option value="10000000-50000000">10M - 50M</option>
                            <option value="50000000-100000000">50M - 100M</option>
                            <option value="100000000-999999999">> 100M</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select id="dateFilter" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                onchange="filterTable()">
                            <option value="">All Dates</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        Showing <span id="visibleCount">{{ $loans->count() }}</span> of {{ $loans->count() }} loans
                    </div>
                    <button onclick="resetFilters()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="loansTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(0)" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>ID</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(1)" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Nasabah</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(2)" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Amount</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(4)" class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Start Date</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($loans as $loan)
                            <tr class="hover:bg-gray-50 transition-colors duration-150" 
                                data-status="{{ $loan->status }}" 
                                data-amount="{{ $loan->loan_amount }}" 
                                data-date="{{ $loan->start_date->format('Y-m-d') }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">#{{ $loan->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-indigo-600">
                                                    {{ $loan->nasabah ? strtoupper(substr($loan->nasabah->name, 0, 1)) : 'N' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $loan->nasabah ? $loan->nasabah->name : 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $loan->nasabah ? $loan->nasabah->email ?? 'No email' : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}</div>
                                    <div class="text-sm text-gray-500">{{ $loan->loan_term ?? 'N/A' }} months</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $loan->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                           ($loan->status == 'active' ? 'bg-blue-100 text-blue-800' : 
                                           ($loan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div>{{ $loan->start_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $loan->start_date->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.loans.show', $loan->id) }}" 
                                           class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150 p-1 rounded-md hover:bg-indigo-50"
                                           title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if ($loan->status == 'pending')
                                            <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline-block" 
                                                  onsubmit="return confirm('Are you sure you want to approve this loan?');">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-blue-600 hover:text-blue-800 transition-colors duration-150 p-1 rounded-md hover:bg-blue-50"
                                                        title="Approve">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="inline-block" 
                                              onsubmit="return confirm('Are you sure you want to delete this loan? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 transition-colors duration-150 p-1 rounded-md hover:bg-red-50"
                                                    title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No loans found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search criteria.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for Enhanced Functionality -->
    <script>
        // Toggle filter panel
        function toggleFilters() {
            const panel = document.getElementById('filterPanel');
            const icon = document.getElementById('filterIcon');
            
            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                panel.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Advanced filtering function
        function filterTable() {
            const nameFilter = document.getElementById('nameFilter').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const amountFilter = document.getElementById('amountFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            
            const table = document.getElementById('loansTable');
            const rows = table.getElementsByTagName('tr');
            let visibleCount = 0;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                let showRow = true;

                // Name filter
                if (nameFilter) {
                    const nasabahCell = row.getElementsByTagName('td')[1];
                    const nasabahText = nasabahCell ? (nasabahCell.textContent || nasabahCell.innerText).toLowerCase() : '';
                    if (!nasabahText.includes(nameFilter)) {
                        showRow = false;
                    }
                }

                // Status filter
                if (statusFilter && showRow) {
                    const status = row.getAttribute('data-status');
                    if (status !== statusFilter) {
                        showRow = false;
                    }
                }

                // Amount filter
                if (amountFilter && showRow) {
                    const amount = parseInt(row.getAttribute('data-amount'));
                    const [min, max] = amountFilter.split('-').map(Number);
                    if (amount < min || (max && amount > max)) {
                        showRow = false;
                    }
                }

                // Date filter
                if (dateFilter && showRow) {
                    const rowDate = new Date(row.getAttribute('data-date'));
                    const now = new Date();
                    let cutoffDate;

                    switch (dateFilter) {
                        case 'today':
                            cutoffDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                            if (rowDate < cutoffDate) showRow = false;
                            break;
                        case 'week':
                            cutoffDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                            if (rowDate < cutoffDate) showRow = false;
                            break;
                        case 'month':
                            cutoffDate = new Date(now.getFullYear(), now.getMonth(), 1);
                            if (rowDate < cutoffDate) showRow = false;
                            break;
                        case 'year':
                            cutoffDate = new Date(now.getFullYear(), 0, 1);
                            if (rowDate < cutoffDate) showRow = false;
                            break;
                    }
                }

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleCount++;
            }

            // Update visible count
            document.getElementById('visibleCount').textContent = visibleCount;
            
            // Show/hide empty state
            const emptyState = document.getElementById('emptyState');
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('nameFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('amountFilter').value = '';
            document.getElementById('dateFilter').value = '';
            filterTable();
        }

        // Sort table functionality
        let sortDirection = {};
        function sortTable(columnIndex) {
            const table = document.getElementById('loansTable');
            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.rows);
            
            // Determine sort direction
            const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            sortDirection[columnIndex] = direction;

            rows.sort((a, b) => {
                let aValue = a.cells[columnIndex].textContent.trim();
                let bValue = b.cells[columnIndex].textContent.trim();

                // Handle numeric values (ID, Amount)
                if (columnIndex === 0) {
                    aValue = parseInt(aValue.replace('#', ''));
                    bValue = parseInt(bValue.replace('#', ''));
                } else if (columnIndex === 2) {
                    aValue = parseInt(aValue.replace(/[^\d]/g, ''));
                    bValue = parseInt(bValue.replace(/[^\d]/g, ''));
                } else if (columnIndex === 4) {
                    aValue = new Date(aValue);
                    bValue = new Date(bValue);
                }

                if (aValue < bValue) return direction === 'asc' ? -1 : 1;
                if (aValue > bValue) return direction === 'asc' ? 1 : -1;
                return 0;
            });

            // Rebuild table
            rows.forEach(row => tbody.appendChild(row));
        }

        // Export functionality
        function exportData() {
            const table = document.getElementById('loansTable');
            const rows = Array.from(table.rows);
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            
            let csv = 'ID,Nasabah,Amount,Status,Start Date\n';
            
            for (let i = 1; i < visibleRows.length; i++) {
                const cells = visibleRows[i].cells;
                const row = [
                    cells[0].textContent.trim(),
                    cells[1].textContent.trim().replace(/\s+/g, ' '),
                    cells[2].textContent.trim(),
                    cells[3].textContent.trim(),
                    cells[4].textContent.trim()
                ];
                csv += row.join(',') + '\n';
            }

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'loans_export_' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Auto-refresh functionality (optional)
        function autoRefresh() {
            // Uncomment the line below to enable auto-refresh every 5 minutes
            // setTimeout(() => { location.reload(); }, 300000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any additional functionality here
            autoRefresh();
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('nameFilter').focus();
                }
                if (e.ctrlKey && e.key === 'e') {
                    e.preventDefault();
                    exportData();
                }
            });
        });
    </script>
</x-admin-layout>