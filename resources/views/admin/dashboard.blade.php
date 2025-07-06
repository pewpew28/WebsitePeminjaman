<x-admin-layout>
    <!-- Ringkasan Keuangan -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Keuangan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pinjaman Aktif -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pinjaman Aktif</p>
                        <p class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($financialSummary['total_active_loans'] , 1) }}
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran Diterima -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pembayaran Diterima</p>
                        <p class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($financialSummary['total_payments_received'] , 1) }}
                        </p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Tunggakan -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tunggakan</p>
                        <p class="text-2xl font-bold text-red-600">
                            @if($financialSummary['total_overdue'] >= 1000000)
                                Rp {{ number_format($financialSummary['total_overdue'] , 1) }}
                            @else
                                Rp {{ number_format($financialSummary['total_overdue'] , 0) }}
                            @endif
                        </p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendapatan Bulan Ini -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendapatan Bulan Ini</p>
                        <p class="text-2xl font-bold text-purple-600">
                            Rp {{ number_format($financialSummary['monthly_income'], 0) }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Nasabah -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Statistik Nasabah & Pinjaman</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Nasabah Baru -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Nasabah Baru</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Bulan Ini</span>
                </div>
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $customerStats['new_customers_count'] }}</div>
                <p class="text-sm text-gray-600">
                    @if($customerStats['new_customers_growth'] >= 0)
                        <span class="text-green-600 font-medium">+{{ $customerStats['new_customers_growth'] }}%</span>
                    @else
                        <span class="text-red-600 font-medium">{{ $customerStats['new_customers_growth'] }}%</span>
                    @endif
                    dari bulan lalu
                </p>
            </div>

            <!-- Pinjaman Baru -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Pinjaman Baru</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Bulan Ini</span>
                </div>
                <div class="text-3xl font-bold text-green-600 mb-2">{{ $customerStats['new_loans_count'] }}</div>
                <p class="text-sm text-gray-600">
                    @if($customerStats['new_loans_growth'] >= 0)
                        <span class="text-green-600 font-medium">+{{ $customerStats['new_loans_growth'] }}%</span>
                    @else
                        <span class="text-red-600 font-medium">{{ $customerStats['new_loans_growth'] }}%</span>
                    @endif
                    dari bulan lalu
                </p>
            </div>

            <!-- Total Nasabah -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Total Nasabah</h3>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Aktif</span>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-2">{{ $customerStats['total_customers'] }}</div>
                <p class="text-sm text-gray-600">
                    <span class="text-blue-600 font-medium">{{ $customerStats['active_customers_percentage'] }}%</span> nasabah aktif
                </p>
            </div>
        </div>
    </div>

    <!-- Tautan Cepat -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.nasabahs.index') }}"
                class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-full group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Manajemen Nasabah</h3>
                <p class="text-sm text-gray-600">Kelola data nasabah, tambah nasabah baru, dan update informasi</p>
            </a>

            <a href="{{ route('admin.loans.index') }}"
                class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-full group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Manajemen Pinjaman</h3>
                <p class="text-sm text-gray-600">Proses pinjaman baru, monitoring pembayaran, dan status pinjaman</p>
            </a>

            <a href=""
                class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 p-3 rounded-full group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Laporan Keuangan</h3>
                <p class="text-sm text-gray-600">Generate laporan bulanan, analisis trend, dan export data</p>
            </a>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-center space-x-4">
                                <div class="{{ $activity['icon_class'] }} p-2 rounded-full">
                                    @if($activity['type'] == 'new_customer')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    @elseif($activity['type'] == 'loan_approved')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    @elseif($activity['type'] == 'payment_received')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>