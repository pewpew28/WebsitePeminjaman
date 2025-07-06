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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
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
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $loans->where('status', 'pending')->count() }}</dd>
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
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    {{ $loans->where('status', 'active')->count() }}</dd>
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
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Amount</dt>
                                <dd class="text-lg font-semibold text-gray-900">Rp
                                    {{ number_format($loans->sum('loan_amount'), 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Advanced Filter Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <button onclick="toggleFilters()" class="flex items-center justify-between w-full text-left">
                    <h3 class="text-lg font-medium text-gray-900">Advanced Filters</h3>
                    <svg id="filterIcon" class="w-5 h-5 text-gray-400 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
            <div id="filterPanel" class="px-6 py-4 space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search by Name -->
                    <div class="relative">
                        <label for="nameFilter" class="block text-sm font-medium text-gray-700 mb-1">Search
                            Nasabah</label>
                        <input type="text" id="nameFilter" placeholder="Enter nasabah name..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            oninput="filterTable()">
                        <svg class="absolute left-3 top-8 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
                        <label for="amountFilter" class="block text-sm font-medium text-gray-700 mb-1">Amount
                            Range</label>
                        <select id="amountFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            onchange="filterTable()">
                            <option value="">All Amounts</option>
                            <option value="0-10000000">
                                < 10 Million</option>
                            <option value="10000000-50000000">10M - 50M</option>
                            <option value="50000000-100000000">50M - 100M</option>
                            <option value="100000000-999999999">> 100M</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Date
                            Range</label>
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
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(0)"
                                    class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>ID</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(1)"
                                    class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Nasabah</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(2)"
                                    class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Amount</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(4)"
                                    class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Start Date</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <button onclick="sortTable(5)"
                                    class="flex items-center space-x-1 hover:text-gray-900">
                                    <span>Assign To</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($loans as $loan)
                            <tr class="hover:bg-gray-50 transition-colors duration-150"
                                data-loan-id="{{ $loan->id }}" data-status="{{ $loan->status }}"
                                data-amount="{{ $loan->loan_amount }}"
                                data-date="{{ $loan->start_date->format('Y-m-d') }}"
                                data-collector-id="{{ $loan->collector_id ?? '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                    #{{ $loan->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-indigo-600">
                                                    {{ $loan->nasabah ? strtoupper(substr($loan->nasabah->name, 0, 1)) : 'N' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $loan->nasabah ? $loan->nasabah->name : 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $loan->nasabah ? $loan->nasabah->email ?? 'No email' : 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $loan->nasabah ? $loan->nasabah->address ?? 'No Address' : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($loan->loan_amount, 0, ',', '.') }}</div>
                                    <div class="text-sm text-gray-500">{{ $loan->loan_term ?? 'N/A' }} months</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $loan->status == 'completed'
                                            ? 'bg-green-100 text-green-800'
                                            : ($loan->status == 'active'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($loan->status == 'pending'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-red-100 text-red-800')) }}">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div>{{ $loan->collector_id ? $loan->collector->name : 'Belum Ada Collector'}}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.loans.show', $loan->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150 p-1 rounded-md hover:bg-indigo-50"
                                            title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button onclick="openAssignModal({{ $loan->id }})"
                                            class="text-green-600 hover:text-green-800 transition-colors duration-150 p-1 rounded-md hover:bg-green-50"
                                            title="Assign To">
                                            <i class="fa-solid fa-list-check"></i>
                                        </button>
                                        @if ($loan->status == 'pending')
                                            <form action="{{ route('admin.loans.approve', $loan->id) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to approve this loan?');">
                                                @csrf
                                                <input type="hidden" value="{{ Auth::user()->id }}"
                                                    name="approver_id">
                                                <button type="submit"
                                                    class="text-blue-600 hover:text-blue-800 transition-colors duration-150 p-1 rounded-md hover:bg-blue-50"
                                                    title="Approve">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this loan? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition-colors duration-150 p-1 rounded-md hover:bg-red-50"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No loans found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search criteria.</p>
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Assign Loan to Collector</h3>
                    <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="assignForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="collectorSelect" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Collector
                        </label>
                        <select id="collectorSelect" name="collector_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Loading collectors...</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors duration-200">
                            Assign Loan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Loan Management System - Complete JavaScript
        // Global variables
        let currentLoanId = null;
        let collectors = [];
        let currentCollectorId = null;
        let originalLoans = [];
        let filteredLoans = [];
        let sortDirection = 'asc';
        let sortColumn = 0;

        // Initialize the system when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeSystem();
        });

        // System initialization
        function initializeSystem() {
            // Store original loan data for filtering
            storeOriginalData();

            // Initialize table functionality
            setupTableEvents();

            // Initialize filter events
            setupFilterEvents();

            // Initialize modal events
            setupModalEvents();

            // Initialize export functionality
            setupExportEvents();

            console.log('Loan Management System initialized successfully');
        }

        // Store original loan data for filtering and sorting
        function storeOriginalData() {
            const table = document.getElementById('loansTable');
            const tbody = table.querySelector('tbody');
            const rows = tbody.querySelectorAll('tr');

            originalLoans = Array.from(rows).map(row => ({
                element: row,
                id: row.getAttribute('data-loan-id'),
                status: row.getAttribute('data-status'),
                amount: parseFloat(row.getAttribute('data-amount')),
                date: new Date(row.getAttribute('data-date')),
                collectorId: row.getAttribute('data-collector-id'),
                nasabahName: row.querySelector('td:nth-child(2) .text-gray-900').textContent.trim(),
                visible: true
            }));

            filteredLoans = [...originalLoans];
            updateVisibleCount();
        }

        // Setup table-related events
        function setupTableEvents() {
            // Add hover effects and click handlers if needed
            const tableRows = document.querySelectorAll('#loansTable tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f9fafb';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
        }

        // Setup filter events
        function setupFilterEvents() {
            // Name filter
            const nameFilter = document.getElementById('nameFilter');
            if (nameFilter) {
                nameFilter.addEventListener('input', debounce(filterTable, 300));
            }

            // Status filter
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', filterTable);
            }

            // Amount filter
            const amountFilter = document.getElementById('amountFilter');
            if (amountFilter) {
                amountFilter.addEventListener('change', filterTable);
            }

            // Date filter
            const dateFilter = document.getElementById('dateFilter');
            if (dateFilter) {
                dateFilter.addEventListener('change', filterTable);
            }
        }

        // Setup modal events
        function setupModalEvents() {
            // Close modal when clicking outside
            const modal = document.getElementById('assignModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeAssignModal();
                    }
                });
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeAssignModal();
                }
            });

            // Form submission
            const assignForm = document.getElementById('assignForm');
            if (assignForm) {
                assignForm.addEventListener('submit', handleAssignFormSubmit);
            }
        }

        // Setup export events
        function setupExportEvents() {
            // Export functionality will be handled by the exportData function
        }

        // Toggle filter panel
        function toggleFilters() {
            const filterPanel = document.getElementById('filterPanel');
            const filterIcon = document.getElementById('filterIcon');

            if (filterPanel.classList.contains('hidden')) {
                filterPanel.classList.remove('hidden');
                filterIcon.style.transform = 'rotate(180deg)';
            } else {
                filterPanel.classList.add('hidden');
                filterIcon.style.transform = 'rotate(0deg)';
            }
        }

        // Main filtering function
        function filterTable() {
            const nameFilter = document.getElementById('nameFilter').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const amountFilter = document.getElementById('amountFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;

            filteredLoans = originalLoans.filter(loan => {
                // Name filter
                if (nameFilter && !loan.nasabahName.toLowerCase().includes(nameFilter)) {
                    return false;
                }

                // Status filter
                if (statusFilter && loan.status !== statusFilter) {
                    return false;
                }

                // Amount filter
                if (amountFilter && !filterByAmount(loan.amount, amountFilter)) {
                    return false;
                }

                // Date filter
                if (dateFilter && !filterByDate(loan.date, dateFilter)) {
                    return false;
                }

                return true;
            });

            updateTableDisplay();
            updateVisibleCount();
        }

        // Filter by amount range
        function filterByAmount(amount, range) {
            if (!range) return true;

            const [min, max] = range.split('-').map(Number);
            return amount >= min && amount <= max;
        }

        // Filter by date range
        function filterByDate(date, range) {
            if (!range) return true;

            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            switch (range) {
                case 'today':
                    return date >= today;
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay());
                    return date >= weekStart;
                case 'month':
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    return date >= monthStart;
                case 'year':
                    const yearStart = new Date(today.getFullYear(), 0, 1);
                    return date >= yearStart;
                default:
                    return true;
            }
        }

        // Update table display based on filtered results
        function updateTableDisplay() {
            const tbody = document.querySelector('#loansTable tbody');
            const emptyState = document.getElementById('emptyState');

            // Hide all rows first
            originalLoans.forEach(loan => {
                loan.element.style.display = 'none';
            });

            // Show filtered rows
            if (filteredLoans.length > 0) {
                filteredLoans.forEach(loan => {
                    loan.element.style.display = '';
                });
                emptyState.classList.add('hidden');
            } else {
                emptyState.classList.remove('hidden');
            }
        }

        // Update visible count
        function updateVisibleCount() {
            const visibleCount = document.getElementById('visibleCount');
            if (visibleCount) {
                visibleCount.textContent = filteredLoans.length;
            }
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('nameFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('amountFilter').value = '';
            document.getElementById('dateFilter').value = '';

            filteredLoans = [...originalLoans];
            updateTableDisplay();
            updateVisibleCount();

            showNotification('Filters reset successfully', 'success');
        }

        // Sort table by column
        function sortTable(columnIndex) {
            const table = document.getElementById('loansTable');
            const tbody = table.querySelector('tbody');

            // Toggle sort direction
            if (sortColumn === columnIndex) {
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                sortDirection = 'asc';
                sortColumn = columnIndex;
            }

            // Sort the filtered loans
            filteredLoans.sort((a, b) => {
                let aValue, bValue;

                switch (columnIndex) {
                    case 0: // ID
                        aValue = parseInt(a.id);
                        bValue = parseInt(b.id);
                        break;
                    case 1: // Nasabah
                        aValue = a.nasabahName.toLowerCase();
                        bValue = b.nasabahName.toLowerCase();
                        break;
                    case 2: // Amount
                        aValue = a.amount;
                        bValue = b.amount;
                        break;
                    case 4: // Date
                        aValue = a.date.getTime();
                        bValue = b.date.getTime();
                        break;
                    case 5: // Date
                        aValue = a.collectorId;
                        bValue = b.collectorId;
                        break;
                    default:
                        return 0;
                }

                if (aValue < bValue) return sortDirection === 'asc' ? -1 : 1;
                if (aValue > bValue) return sortDirection === 'asc' ? 1 : -1;
                return 0;
            });

            // Reorder DOM elements
            filteredLoans.forEach(loan => {
                tbody.appendChild(loan.element);
            });

            // Update sort indicators
            updateSortIndicators(columnIndex);

            showNotification(`Sorted by column ${columnIndex + 1} (${sortDirection})`, 'success');
        }

        // Update sort indicators in table headers
        function updateSortIndicators(activeColumn) {
            const headers = document.querySelectorAll('#loansTable thead button');
            headers.forEach((header, index) => {
                const svg = header.querySelector('svg');
                if (svg) {
                    if (index === activeColumn) {
                        svg.style.transform = sortDirection === 'asc' ? 'rotate(0deg)' : 'rotate(180deg)';
                        svg.style.color = '#4f46e5';
                    } else {
                        svg.style.transform = 'rotate(0deg)';
                        svg.style.color = '#9ca3af';
                    }
                }
            });
        }

        // Export data functionality
        function exportData() {
            const exportButton = document.querySelector('[onclick="exportData()"]');
            const originalText = exportButton.textContent;

            exportButton.disabled = true;
            exportButton.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Exporting...
    `;

            // Create CSV data
            const csvData = generateCSVData();

            // Create and download file
            const blob = new Blob([csvData], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `loans_export_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Reset button
            setTimeout(() => {
                exportButton.disabled = false;
                exportButton.innerHTML = originalText;
                showNotification('Data exported successfully', 'success');
            }, 1000);
        }

        // Fungsi helper untuk parsing amount
        function parseAmount(amountStr) {
            if (!amountStr) return 0;

            // Handle different currency formats
            const cleanAmount = amountStr.toString()
                .replace(/[^\d.,]/g, '') // Remove Rp, spaces, etc.
                .replace(/\./g, '') // Remove thousand separators
                .replace(',', '.'); // Convert comma decimal to dot

            const parsed = parseFloat(cleanAmount);
            return isNaN(parsed) ? 0 : parsed;
        }

        // Perbaikan di storeOriginalData()
        function storeOriginalData() {
            const table = document.getElementById('loansTable');
            const tbody = table.querySelector('tbody');
            const rows = tbody.querySelectorAll('tr');

            originalLoans = Array.from(rows).map(row => {
                // Ambil amount dari data attribute atau DOM element
                let amount = row.getAttribute('data-amount');

                // Jika data-amount kosong, ambil dari DOM
                if (!amount) {
                    const amountCell = row.querySelector('td:nth-child(3)'); // Sesuaikan selector
                    amount = amountCell?.textContent || '0';
                }

                return {
                    element: row,
                    id: row.getAttribute('data-loan-id'),
                    status: row.getAttribute('data-status'),
                    amount: parseAmount(amount), // ← Gunakan fungsi parsing
                    date: new Date(row.getAttribute('data-date')),
                    collectorId: row.getAttribute('data-collector-id'),
                    nasabahName: row.querySelector('td:nth-child(2) .text-gray-900').textContent.trim(),
                    visible: true
                };
            });

            filteredLoans = [...originalLoans];
            updateVisibleCount();
        }

        // Perbaikan di generateCSVData()
        function generateCSVData() {
            const headers = ['ID', 'Nasabah Name', 'Email', 'Address', 'Loan Amount', 'Loan Term', 'Status', 'Start Date',
                'Created At'
            ];
            let csvContent = headers.join(',') + '\n';

            filteredLoans.forEach(loan => {
                const row = loan.element;
                const cells = row.querySelectorAll('td');

                const id = loan.id;
                const nasabahName = loan.nasabahName;
                const email = cells[1].querySelector('.text-gray-500').textContent.trim();
                const address = cells[1].querySelectorAll('.text-gray-500')[1]?.textContent.trim() || '';

                // Format amount dengan benar
                const amount = typeof loan.amount === 'number' && !isNaN(loan.amount) ?
                    loan.amount.toLocaleString('id-ID') :
                    '0';

                const term = cells[2].querySelector('.text-gray-500').textContent.trim();
                const status = cells[3].querySelector('span').textContent.trim();
                const startDate = cells[4].querySelector('div').textContent.trim();
                const createdAt = cells[4].querySelector('.text-gray-400').textContent.trim();

                const rowData = [
                    id,
                    `"${nasabahName}"`,
                    `"${email}"`,
                    `"${address}"`,
                    `"${amount}"`, // ← Sudah diformat dengan benar
                    `"${term}"`,
                    status,
                    `"${startDate}"`,
                    `"${createdAt}"`
                ];

                csvContent += rowData.join(',') + '\n';
            });

            return csvContent;
        }

        // Modal functions
        async function openAssignModal(loanId) {
            currentLoanId = loanId;
            const modal = document.getElementById('assignModal');
            const collectorSelect = document.getElementById('collectorSelect');
            const form = document.getElementById('assignForm');

            // Show modal
            modal.classList.remove('hidden');

            // Set form action
            form.action = `/admin/loans/${loanId}/assign`;

            // Show loading state
            collectorSelect.innerHTML = '<option value="">Loading collectors...</option>';

            try {
                // Fetch collectors and current loan data from the API
                const response = await fetch(`/admin/loans/${loanId}/assign`);
                if (!response.ok) {
                    throw new Error('Failed to fetch collectors');
                }

                const data = await response.json();
                collectors = data.collectors || data;

                // Get current collector_id from the loan row
                const loanRow = document.querySelector(`tr[data-loan-id="${loanId}"]`);
                currentCollectorId = loanRow ? loanRow.getAttribute('data-collector-id') : null;

                // Populate select options
                collectorSelect.innerHTML = '<option value="">Choose a collector</option>';
                collectors.forEach(collector => {
                    const option = document.createElement('option');
                    option.value = collector.id;
                    option.textContent = `${collector.name} (${collector.address})`;

                    // Auto-select current collector if exists
                    if (currentCollectorId && collector.id == currentCollectorId) {
                        option.selected = true;
                    }

                    collectorSelect.appendChild(option);
                });

                // Remove any existing helper text
                const existingHelp = collectorSelect.parentNode.querySelectorAll('small');
                existingHelp.forEach(help => help.remove());

                // Add helper text
                const helpContainer = document.createElement('div');
                helpContainer.className = 'mt-1';

                if (!currentCollectorId) {
                    helpContainer.innerHTML =
                        '<small class="text-gray-500">No collector currently assigned to this loan</small>';
                } else {
                    const currentCollector = collectors.find(c => c.id == currentCollectorId);
                    if (currentCollector) {
                        helpContainer.innerHTML =
                            `<small class="text-blue-600">Currently assigned to: ${currentCollector.name}</small>`;
                    }
                }

                collectorSelect.parentNode.appendChild(helpContainer);

            } catch (error) {
                console.error('Error fetching collectors:', error);
                collectorSelect.innerHTML = '<option value="">Error loading collectors</option>';
                showNotification('Error loading collectors. Please try again.', 'error');
            }
        }

        function closeAssignModal() {
            const modal = document.getElementById('assignModal');
            const form = document.getElementById('assignForm');
            const collectorSelect = document.getElementById('collectorSelect');

            modal.classList.add('hidden');
            form.reset();

            // Remove any helper text
            const helpTexts = collectorSelect.parentNode.querySelectorAll('small, div');
            helpTexts.forEach(text => {
                if (text.tagName === 'DIV' && text.className.includes('mt-1')) {
                    text.remove();
                }
            });

            currentLoanId = null;
            currentCollectorId = null;
        }

        // Handle form submission
        async function handleAssignFormSubmit(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const newCollectorId = formData.get('collector_id');

            if (!newCollectorId) {
                showNotification('Please select a collector', 'error');
                return;
            }

            // Check if the collector is being changed
            if (currentCollectorId && currentCollectorId === newCollectorId) {
                showNotification('This loan is already assigned to the selected collector', 'info');
                return;
            }

            // Add both old and new collector IDs to the form data
            formData.append('old_collector_id', currentCollectorId || '');
            formData.append('new_collector_id', newCollectorId);

            // Show loading state
            const submitButton = e.target.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Assigning...
    `;

            try {
                const response = await fetch(e.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    const newCollector = collectors.find(c => c.id == newCollectorId);
                    const oldCollector = currentCollectorId ? collectors.find(c => c.id == currentCollectorId) : null;

                    let message = result.message;
                    if (!message) {
                        if (oldCollector) {
                            message =
                                `Loan reassigned from ${oldCollector.name} to ${newCollector ? newCollector.name : 'new collector'}`;
                        } else {
                            message = `Loan assigned to ${newCollector ? newCollector.name : 'collector'}`;
                        }
                    }

                    showNotification(message, 'success');
                    closeAssignModal();

                    // Update the loan row data attributes
                    const loanRow = document.querySelector(`tr[data-loan-id="${currentLoanId}"]`);
                    if (loanRow) {
                        loanRow.setAttribute('data-collector-id', newCollectorId);
                    }

                    // Update the original loans data
                    const loanIndex = originalLoans.findIndex(loan => loan.id === currentLoanId);
                    if (loanIndex !== -1) {
                        originalLoans[loanIndex].collectorId = newCollectorId;
                    }

                    // Refresh the page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(result.message || 'Failed to assign loan');
                }

            } catch (error) {
                console.error('Error assigning loan:', error);
                showNotification(error.message || 'Error assigning loan. Please try again.', 'error');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        }

        // Utility functions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Show notification function
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => notification.remove());

            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-0 ${
        type === 'success' 
            ? 'bg-green-50 text-green-800 border border-green-200' 
            : type === 'error'
            ? 'bg-red-50 text-red-800 border border-red-200'
            : 'bg-blue-50 text-blue-800 border border-blue-200'
    }`;

            const iconPath = type === 'success' ?
                'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                type === 'error' ?
                'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' :
                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

            notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
            </svg>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-current opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Advanced search functionality
        function performAdvancedSearch() {
            const searchTerm = document.getElementById('nameFilter').value.toLowerCase();

            if (searchTerm.length < 2) {
                filterTable();
                return;
            }

            // Search in multiple fields
            filteredLoans = originalLoans.filter(loan => {
                const row = loan.element;
                const cells = row.querySelectorAll('td');

                // Search in ID, name, email, address
                const searchableText = [
                    loan.id,
                    loan.nasabahName,
                    cells[1].querySelector('.text-gray-500')?.textContent || '',
                    cells[1].querySelectorAll('.text-gray-500')[1]?.textContent || ''
                ].join(' ').toLowerCase();

                return searchableText.includes(searchTerm);
            });

            updateTableDisplay();
            updateVisibleCount();
        }

        // Bulk operations
        function initializeBulkOperations() {
            // Add checkboxes to table if not present
            const headerRow = document.querySelector('#loansTable thead tr');
            if (!headerRow.querySelector('input[type="checkbox"]')) {
                const checkboxHeader = document.createElement('th');
                checkboxHeader.className =
                    'px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider';
                checkboxHeader.innerHTML =
                    '<input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">';
                headerRow.insertBefore(checkboxHeader, headerRow.firstChild);

                // Add checkboxes to each row
                const bodyRows = document.querySelectorAll('#loansTable tbody tr');
                bodyRows.forEach(row => {
                    const checkboxCell = document.createElement('td');
                    checkboxCell.className = 'px-6 py-4 whitespace-nowrap';
                    checkboxCell.innerHTML =
                        `<input type="checkbox" class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="${row.getAttribute('data-loan-id')}">`;
                    row.insertBefore(checkboxCell, row.firstChild);
                });

                // Add select all functionality
                document.getElementById('selectAll').addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.row-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            }
        }

        // Print functionality
        function printTable() {
            const printWindow = window.open('', '_blank');
            const tableHTML = document.getElementById('loansTable').outerHTML;

            printWindow.document.write(`
        <html>
        <head>
            <title>Loan Management Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .no-print { display: none; }
                @media print {
                    .no-print { display: none !important; }
                }
            </style>
        </head>
        <body>
            <h1>Loan Management Report</h1>
            <p>Generated on: ${new Date().toLocaleDateString()}</p>
            ${tableHTML}
        </body>
        </html>
    `);

            printWindow.document.close();
            printWindow.print();
        }

        // Enhanced error handling
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
            showNotification('An unexpected error occurred. Please refresh the page.', 'error');
        });

        // Performance monitoring
        function measurePerformance(operationName, fn) {
            const start = performance.now();
            const result = fn();
            const end = performance.now();
            console.log(`${operationName} took ${end - start} milliseconds`);
            return result;
        }

        // Auto-refresh functionality
        let autoRefreshInterval = null;

        function startAutoRefresh(intervalMinutes = 5) {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }

            autoRefreshInterval = setInterval(() => {
                location.reload();
            }, intervalMinutes * 60 * 1000);

            showNotification(`Auto-refresh enabled (${intervalMinutes} minutes)`, 'success');
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
                showNotification('Auto-refresh disabled', 'success');
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+F or Cmd+F for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                document.getElementById('nameFilter').focus();
            }

            // Ctrl+E or Cmd+E for export
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                exportData();
            }

            // Ctrl+R or Cmd+R for reset filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                resetFilters();
            }
        });

        // Initialize tooltips
        function initializeTooltips() {
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function(e) {
                    const tooltip = document.createElement('div');
                    tooltip.className =
                        'absolute z-50 px-2 py-1 text-sm text-white bg-gray-900 rounded shadow-lg';
                    tooltip.textContent = this.getAttribute('title');
                    tooltip.style.left = e.pageX + 10 + 'px';
                    tooltip.style.top = e.pageY + 10 + 'px';
                    tooltip.id = 'tooltip';

                    document.body.appendChild(tooltip);
                    this.removeAttribute('title');
                });

                element.addEventListener('mouseleave', function() {
                    const tooltip = document.getElementById('tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                    this.setAttribute('title', this.getAttribute('data-original-title') || '');
                });
            });
        }

        // Initialize system on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeSystem();
            initializeTooltips();

            // Add keyboard shortcuts info
            console.log('Keyboard shortcuts:');
            console.log('Ctrl+F: Focus search');
            console.log('Ctrl+E: Export data');
            console.log('Ctrl+R: Reset filters');
        });

        // Service worker for offline support (optional)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>
</x-admin-layout>
