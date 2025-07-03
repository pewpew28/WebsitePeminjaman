<x-admin-layout title="Detail Nasabah">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $nasabah->name ?? 'Nama Tidak Tersedia' }}</h1>
                        <p class="text-gray-600 mt-1">ID: #{{ $nasabah->id }} | Status: 
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $nasabah->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $nasabah->status === 'active' ? 'Aktif' : ucfirst($nasabah->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.nasabahs.edit', $nasabah->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Edit
                        </a>
                        <a href="{{ route('admin.nasabahs.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Informasi Personal -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Personal</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                        <dd class="text-sm text-gray-900 font-medium">{{ $nasabah->name ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nomor KTP</dt>
                                        <dd class="text-sm text-gray-900 font-mono">{{ $nasabah->id_card_number ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $nasabah->email ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ $nasabah->date_of_birth ? \Carbon\Carbon::parse($nasabah->date_of_birth)->format('d/m/Y') : '-' }}
                                        </dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                        <dd class="text-sm text-gray-900">{{ $nasabah->phone_number ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ $nasabah->gender === 'male' ? 'Laki-laki' : ($nasabah->gender === 'female' ? 'Perempuan' : ($nasabah->gender ?? '-')) }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Pekerjaan</dt>
                                        <dd class="text-sm text-gray-900">{{ $nasabah->occupation ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Pendapatan Bulanan</dt>
                                        <dd class="text-sm text-green-600 font-semibold">
                                            {{ $nasabah->monthly_income ? 'Rp ' . number_format($nasabah->monthly_income, 0, ',', '.') : '-' }}
                                        </dd>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="text-sm text-gray-900">{{ $nasabah->address ?? '-' }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Account Info -->
                    @if ($nasabah->user)
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Akun</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Username</dt>
                                        <dd class="text-sm text-gray-900">{{ $nasabah->user->name ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $nasabah->user->email ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status Verifikasi</dt>
                                        <dd>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $nasabah->user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $nasabah->user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Bergabung Sejak</dt>
                                        <dd class="text-sm text-gray-900">
                                            {{ $nasabah->user->created_at ? $nasabah->user->created_at->format('d/m/Y') : '-' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Riwayat Pinjaman -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Riwayat Pinjaman</h2>
                                <a href="{{ route('admin.loans.create', ['nasabah_id' => $nasabah->id]) }}"
                                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    + Tambah Pinjaman
                                </a>
                            </div>

                            @if ($nasabah->loans->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($nasabah->loans as $index => $loan)
                                        <div class="border border-gray-200 rounded-lg">
                                            <!-- Loan Header -->
                                            <div class="p-4 bg-gray-50 cursor-pointer" 
                                                 onclick="toggleLoanDetails({{ $index }})">
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center space-x-4">
                                                        <div>
                                                            <h3 class="font-semibold text-gray-900">
                                                                Pinjaman #{{ $loan->id }}
                                                            </h3>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $loan->created_at->format('d/m/Y') }}
                                                            </p>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-semibold text-gray-900">
                                                                Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}
                                                            </p>
                                                            <p class="text-sm text-gray-600">
                                                                {{ $loan->loan_term }} {{ $loan->term_unit }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        @php
                                                            $statusColors = [
                                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                                'approved' => 'bg-green-100 text-green-800',
                                                                'rejected' => 'bg-red-100 text-red-800',
                                                                'completed' => 'bg-blue-100 text-blue-800',
                                                                'overdue' => 'bg-red-100 text-red-800',
                                                                'active' => 'bg-green-100 text-green-800',
                                                            ];
                                                            $statusLabels = [
                                                                'pending' => 'Menunggu',
                                                                'approved' => 'Disetujui',
                                                                'rejected' => 'Ditolak',
                                                                'completed' => 'Selesai',
                                                                'overdue' => 'Terlambat',
                                                                'active' => 'Aktif',
                                                            ];
                                                        @endphp
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                            {{ $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                            {{ $statusLabels[$loan->status] ?? ucfirst($loan->status) }}
                                                        </span>
                                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" 
                                                             id="chevron-{{ $index }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Loan Details (Hidden by default) -->
                                            <div id="loan-details-{{ $index }}" class="hidden p-4 border-t border-gray-200">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Bunga</dt>
                                                        <dd class="text-sm text-gray-900">{{ $loan->interest_rate }}%</dd>
                                                    </div>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Progress Pembayaran</dt>
                                                        <dd>
                                                            @if ($loan->installments && $loan->installments->count() > 0)
                                                                @php
                                                                    $totalInstallments = $loan->installments->count();
                                                                    $paidInstallments = $loan->installments->where('status', 'paid')->count();
                                                                    $progressPercentage = $totalInstallments > 0 ? ($paidInstallments / $totalInstallments) * 100 : 0;
                                                                @endphp
                                                                <div class="flex items-center space-x-2">
                                                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                                        <div class="bg-blue-600 h-2 rounded-full" 
                                                                             style="width: {{ $progressPercentage }}%"></div>
                                                                    </div>
                                                                    <span class="text-sm text-gray-600">
                                                                        {{ $paidInstallments }}/{{ $totalInstallments }}
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span class="text-sm text-gray-400">Belum ada cicilan</span>
                                                            @endif
                                                        </dd>
                                                    </div>
                                                </div>

                                                <!-- Installments Table -->
                                                @if ($loan->installments && $loan->installments->count() > 0)
                                                    <div class="mt-4">
                                                        <h4 class="font-medium text-gray-900 mb-3">Jadwal Cicilan</h4>
                                                        <div class="overflow-x-auto">
                                                            <table class="min-w-full text-sm">
                                                                <thead class="bg-gray-50">
                                                                    <tr>
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-700">Cicilan</th>
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-700">Jatuh Tempo</th>
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-700">Jumlah</th>
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-700">Status</th>
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-700">Tgl Bayar</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-200">
                                                                    @foreach ($loan->installments as $installment)
                                                                        <tr>
                                                                            <td class="px-3 py-2 text-gray-900">
                                                                                #{{ $installment->installment_number }}
                                                                            </td>
                                                                            <td class="px-3 py-2 text-gray-900">
                                                                                {{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}
                                                                            </td>
                                                                            <td class="px-3 py-2 text-gray-900 font-medium">
                                                                                Rp {{ number_format($installment->principal_amount, 0, ',', '.') }}
                                                                            </td>
                                                                            <td class="px-3 py-2">
                                                                                @php
                                                                                    $installmentStatusColors = [
                                                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                                                        'paid' => 'bg-green-100 text-green-800',
                                                                                        'overdue' => 'bg-red-100 text-red-800',
                                                                                    ];
                                                                                @endphp
                                                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                                                    {{ $installmentStatusColors[$installment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                                                    {{ ucfirst($installment->status) }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="px-3 py-2 text-gray-900">
                                                                                {{ $installment->payment_date ? \Carbon\Carbon::parse($installment->payment_date)->format('d/m/Y') : '-' }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Actions -->
                                                <div class="mt-4 flex justify-end space-x-3">
                                                    <a href="{{ route('admin.loans.show', $loan->id) }}"
                                                       class="text-indigo-600 hover:text-indigo-900 font-medium">
                                                        Detail Lengkap
                                                    </a>
                                                    @if ($loan->status === 'pending')
                                                        <a href="{{ route('admin.loans.edit', $loan->id) }}"
                                                           class="text-blue-600 hover:text-blue-900 font-medium">
                                                            Edit
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Pinjaman</h3>
                                    <p class="mt-2 text-gray-500">Nasabah ini belum memiliki riwayat pinjaman.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Statistik Pinjaman -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik Pinjaman</h2>
                            <div class="space-y-3">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="text-xl font-bold text-blue-600">{{ $nasabah->loans->count() }}</div>
                                    <div class="text-sm text-blue-600">Total Pinjaman</div>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <div class="text-xl font-bold text-green-600">
                                        {{ $nasabah->loans->where('status', 'approved')->count() }}
                                    </div>
                                    <div class="text-sm text-green-600">Disetujui</div>
                                </div>
                                <div class="bg-purple-50 p-3 rounded-lg">
                                    <div class="text-xl font-bold text-purple-600">
                                        {{ $nasabah->loans->where('status', 'completed')->count() }}
                                    </div>
                                    <div class="text-sm text-purple-600">Selesai</div>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-lg">
                                    <div class="text-xl font-bold text-yellow-600">
                                        {{ $nasabah->loans->where('status', 'pending')->count() }}
                                    </div>
                                    <div class="text-sm text-yellow-600">Pending</div>
                                </div>
                                <div class="bg-indigo-50 p-3 rounded-lg">
                                    <div class="text-lg font-bold text-indigo-600">
                                        Rp {{ number_format($nasabah->loans->sum('loan_amount'), 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-indigo-600">Total Nilai</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Collector Tasks -->
                    @if ($nasabah->collectorTasks->count() > 0)
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tugas Kolektor</h2>
                                <div class="space-y-3">
                                    <div class="bg-orange-50 p-3 rounded-lg">
                                        <div class="text-xl font-bold text-orange-600">
                                            {{ $nasabah->collectorTasks->count() }}
                                        </div>
                                        <div class="text-sm text-orange-600">Total Tugas</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <div class="text-lg font-bold text-gray-600">
                                            {{ $nasabah->collectorTasks->where('status', 'pending')->count() }}
                                        </div>
                                        <div class="text-sm text-gray-600">Pending</div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg">
                                        <div class="text-sm font-bold text-green-600">
                                            Rp {{ number_format($nasabah->collectorTasks->sum('amount_collected_during_task'), 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-green-600">Terkumpul</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Info Tambahan -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Info Tambahan</h2>
                            <div class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terdaftar</dt>
                                    <dd class="text-sm text-gray-900">{{ $nasabah->created_at->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Update Terakhir</dt>
                                    <dd class="text-sm text-gray-900">{{ $nasabah->updated_at->format('d/m/Y') }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLoanDetails(index) {
            const details = document.getElementById(`loan-details-${index}`);
            const chevron = document.getElementById(`chevron-${index}`);
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                details.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }
    </script>
</x-admin-layout>