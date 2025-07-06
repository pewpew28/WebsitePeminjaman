<x-user-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Today Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Tugas Hari Ini</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $dashboardData['stats']['today_tasks_count'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Tugas Terlambat</div>
                                <div class="text-2xl font-bold text-red-600">
                                    {{ $dashboardData['stats']['overdue_tasks_count'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Today -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Selesai Hari Ini</div>
                                <div class="text-2xl font-bold text-green-600">
                                    {{ $dashboardData['stats']['completed_today_count'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today Revenue -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</div>
                                <div class="text-2xl font-bold text-yellow-600">Rp
                                    {{ number_format($dashboardData['stats']['today_revenue'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Tingkat Penyelesaian Hari Ini</h3>
                            <p class="text-sm text-gray-600">{{ $dashboardData['stats']['completion_rate_today'] }}%
                                dari total tugas</p>
                        </div>
                        <div class="text-3xl font-bold text-blue-600">
                            {{ $dashboardData['stats']['completion_rate_today'] }}%</div>
                    </div>
                    <div class="mt-4">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full"
                                style="width: {{ $dashboardData['stats']['completion_rate_today'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today Tasks List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                        <h3 class="text-lg font-medium text-gray-900">Tugas Hari Ini</h3>
                        <button onclick="location.reload()"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-2 rounded-full text-sm font-medium self-start sm:self-auto">
                            Refresh
                        </button>
                    </div>

                    @if ($dashboardData['today_tasks']->count() > 0)
                        <div class="space-y-3 sm:space-y-4">
                            @php
                                $groupedTasks = $dashboardData['today_tasks']->groupBy('nasabah_id');
                            @endphp
                            @foreach ($groupedTasks as $nasabahId => $tasks)
                                @php
                                    $nasabah = optional($tasks->first())->nasabah;

                                    $today = now()->startOfDay();

                                    $hasOverdue = $tasks->contains(
                                        fn($task) => optional($task->due_date)->startOfDay()?->lessThan($today),
                                    );

                                    $totalAmount = $tasks->sum(function ($task) {
                                        $installment = $task->installment;

                                        if (!$installment) {
                                            return 0;
                                        }

                                        if (($installment->remaining_amount ?? 0) > 0) {
                                            return $installment->remaining_amount;
                                        }

                                        return $installment->total_due_amount ?? 0;
                                    });
                                @endphp
                                <div
                                    class="border rounded-lg overflow-hidden @if ($hasOverdue) border-red-300 bg-red-50 @else border-gray-200 @endif">
                                    <!-- Header Nasabah -->
                                    <div class="p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                                        onclick="toggleNasabahTasks({{ $nasabahId }})">
                                        <div class="space-y-3">
                                            <!-- Nama dan Badge -->
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                                        <h4 class="font-medium text-gray-900 text-base">
                                                            {{ $nasabah->name }}</h4>
                                                        @if ($hasOverdue)
                                                            <span
                                                                class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full whitespace-nowrap">
                                                                Ada Terlambat
                                                            </span>
                                                        @endif
                                                        <span
                                                            class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full whitespace-nowrap">
                                                            {{ $tasks->count() }} tugas
                                                        </span>
                                                    </div>

                                                    <!-- Info Total dan Alamat -->
                                                    <div class="space-y-1">
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <span class="font-medium">Total: Rp
                                                                {{ number_format($totalAmount, 0, ',', '.') }}</span>
                                                        </div>
                                                        <div class="flex items-start text-sm text-gray-600">
                                                            <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                            </svg>
                                                            <span
                                                                class="break-words">{{ $nasabah->address ?? 'Alamat tidak tersedia' }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Chevron Icon -->
                                                <div class="ml-2 flex-shrink-0">
                                                    <svg class="w-5 h-5 text-gray-400 chevron-icon" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <button
                                                    onclick="event.stopPropagation(); showPaymentModal({{ $nasabahId }}, '{{ $nasabah->name }}', {{ $totalAmount }}, {{ json_encode($tasks->pluck('installment_id')->toArray()) }})"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                        </path>
                                                    </svg>
                                                    Bayar
                                                </button>
                                                <button
                                                    onclick="event.stopPropagation(); showDetailModal({{ $nasabahId }}, '{{ $nasabah->name }}')"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detail Tasks (Collapsed by default) -->
                                    <div id="nasabah-tasks-{{ $nasabahId }}" class="hidden bg-gray-50 border-t">
                                        <div class="p-4 space-y-3">
                                            @foreach ($tasks as $task)
                                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                    <div class="space-y-2">
                                                        <!-- Header Task -->
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="font-medium text-sm">Installment
                                                                #{{ $task->installment_id }}</span>
                                                            @if ($task->due_date->startOfDay()->lessThan(now()->startOfDay()))
                                                                <span
                                                                    class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                                                    Terlambat
                                                                </span>
                                                            @endif
                                                            <span
                                                                class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">
                                                                {{ ucfirst($task->status) }}
                                                            </span>
                                                        </div>

                                                        <!-- Task Info -->
                                                        <div class="space-y-1">
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                                <span>{{ $task->due_date->format('d M Y') }}</span>
                                                            </div>
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <span class="font-medium">Rp
                                                                    {{ number_format($task->installment->remaining_amount <= 0 ? $task->installment->total_due_amount : $task->installment->remaining_amount, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>

                                                        @if ($task->notes)
                                                            <p class="text-sm text-gray-500 mt-2 break-words">
                                                                {{ $task->notes }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada tugas hari ini</h3>
                            <p class="text-gray-500 text-center px-4">Semua tugas sudah selesai atau tidak ada tugas
                                yang dijadwalkan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Completed Tasks Today (if any) -->
            @if ($dashboardData['completed_today']->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tugas Selesai Hari Ini</h3>
                        <div class="space-y-3">
                            @foreach ($dashboardData['completed_today'] as $task)
                                <div class="border border-green-200 bg-green-50 rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h4 class="font-medium text-gray-900">{{ $task->nasabah->name }}</h4>
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                    Selesai
                                                </span>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600">
                                                <span>Dikunjungi:
                                                    {{ $task->actual_visit_date->format('d M Y H:i') }}</span>
                                                <span>Rp
                                                    {{ number_format($task->amount_collected_during_task ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] flex flex-col">
            <!-- Header Modal - Fixed -->
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <h3 class="text-lg font-medium text-gray-900">Proses Pembayaran</h3>
            </div>

            <!-- Content Modal - Scrollable -->
            <div class="flex-1 overflow-y-auto p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nasabah</label>
                        <p id="paymentNasabahName" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Tagihan</label>
                        <p id="paymentTotalAmount" class="mt-1 text-lg font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <label for="paymentAmount" class="block text-sm font-medium text-gray-700">Jumlah
                            Dibayar</label>
                        <input type="number" id="paymentAmount"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Metode
                            Pembayaran</label>
                        <select id="paymentMethod" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label for="paymentFile" class="block text-sm font-medium text-gray-700">
                            Bukti Pembayaran (Opsional)
                        </label>
                        <input type="file" id="paymentFile" accept="image/*"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 5MB</p>
                    </div>

                    <!-- Preview area untuk file yang dipilih -->
                    <div id="filePreview" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Preview</label>
                        <div id="previewContent" class="mt-1 p-2 border border-gray-300 rounded-md">
                            <!-- Preview akan muncul disini -->
                        </div>
                    </div>
                    <div>
                        <label for="paymentNotes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea id="paymentNotes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <!-- Footer Modal - Fixed -->
            <div class="p-6 border-t border-gray-200 flex-shrink-0">
                <div class="flex justify-end space-x-3">
                    <button onclick="closePaymentModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button id="paymentSubmitBtn" onclick="processPayment()"
                        class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700">
                        Proses Pembayaran
                    </button>
                    <div id="loadingSpinner" class="hidden mt-2 text-center">
                        <svg class="animate-spin w-6 h-6 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 mt-1">Memproses pembayaran...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 z-50 flex justify-center items-center px-4">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/50" onclick="closeDetailModal()"></div>

        <!-- Modal Box -->
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] flex flex-col relative z-10">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Detail Nasabah</h3>
                <button onclick="closeDetailModal()" class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
            </div>

            <!-- Scrollable Content -->
            <div id="detailContent" class="p-4 overflow-y-auto flex-1 space-y-4">
                <div class="text-center text-gray-500">Memuat data...</div>
            </div>
        </div>
    </div>



    <script>
        let currentNasabahId = null;
        let currentTotalAmount = 0;
        let installment = [];

        function toggleNasabahTasks(nasabahId) {
            const element = document.getElementById(`nasabah-tasks-${nasabahId}`);
            const chevron = element.previousElementSibling.querySelector('.chevron-icon');

            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                element.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        function showPaymentModal(nasabahId, nasabahName, totalAmount, installmentIds = []) {
            currentNasabahId = nasabahId;
            currentTotalAmount = totalAmount;
            installment = installmentIds;
            document.getElementById('paymentNasabahName').textContent = nasabahName;
            document.getElementById('paymentTotalAmount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
            document.getElementById('paymentAmount').value = totalAmount;
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
            // Reset form
            document.getElementById('paymentAmount').value = '';
            document.getElementById('paymentMethod').value = 'cash';
            document.getElementById('paymentNotes').value = '';
            document.getElementById('paymentFile').value = ''; // Reset file input
            document.getElementById('filePreview').classList.add('hidden'); // Hide preview
        }

        function processPayment() {
            const amount = document.getElementById('paymentAmount').value;
            const method = document.getElementById('paymentMethod').value;
            const notes = document.getElementById('paymentNotes').value;
            const fileInput = document.getElementById('paymentFile');
            const file = fileInput.files[0];

            const submitBtn = document.getElementById('paymentSubmitBtn');
            const spinner = document.getElementById('loadingSpinner');

            if (!amount || amount <= 0) {
                alert('Jumlah pembayaran harus lebih dari 0');
                return;
            }

            if (!confirm('Apakah Anda yakin ingin memproses pembayaran ini?')) {
                return;
            }

            const formData = new FormData();
            formData.append('nasabah_id', currentNasabahId);
            formData.append('paid_amount', amount);
            formData.append('payment_method', method);
            formData.append('notes', notes);
            formData.append('installment_ids', JSON.stringify(installment));

            if (file) {
                formData.append('payment_proof', file);
            }

            // Disable tombol & tampilkan spinner
            submitBtn.classList.add('hidden');
            spinner.classList.remove('hidden');

            fetch('/collector/payment', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pembayaran berhasil diproses!');
                        closePaymentModal();
                        location.reload();
                    } else {
                        alert('Gagal memproses pembayaran: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses pembayaran');
                })
                .finally(() => {
                    // Tampilkan lagi tombol & sembunyikan spinner
                    submitBtn.classList.remove('hidden');
                    spinner.classList.add('hidden');
                });
        }

        function showDetailModal(nasabahId) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            content.innerHTML = `
        <div class="text-center py-4 text-gray-500">Memuat data...</div>
    `;

            fetch(`/collector/nasabah/${nasabahId}/detail`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        content.innerHTML = `
                    <div class="text-center py-4 text-red-500">Gagal memuat detail nasabah</div>
                `;
                        return;
                    }

                    const nasabah = data.nasabah;
                    const loan = data.loan;
                    const payments = data.recent_payments;

                    content.innerHTML = `
                <div class="space-y-6">
                    <!-- Informasi Nasabah -->
                    <section>
                        <h4 class="font-semibold text-lg text-gray-800 mb-2">📄 Informasi Nasabah</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <p><span class="font-medium">Nama:</span> ${nasabah.name}</p>
                            <p><span class="font-medium">Alamat:</span> ${nasabah.address || '-'}</p>
                            <p><span class="font-medium">Telepon:</span> ${nasabah.phone_number || '-'}</p>
                            <p><span class="font-medium">Email:</span> ${nasabah.email || '-'}</p>
                        </div>
                    </section>

                    <!-- Informasi Pinjaman -->
                    ${loan ? `
                                        <section>
                                            <h4 class="font-semibold text-lg text-gray-800 mb-2">💰 Informasi Pinjaman</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                                <p><span class="font-medium">Total Pinjaman:</span> Rp ${loan.total_amount.toLocaleString('id-ID')}</p>
                                                <p><span class="font-medium">Sisa Pinjaman:</span> Rp ${loan.remaining_amount.toLocaleString('id-ID')}</p>
                                                <p><span class="font-medium">Jangka Waktu:</span> ${loan.loan_term} bulan</p>
                                                <p><span class="font-medium">Status:</span> ${loan.status}</p>
                                            </div>
                                        </section>
                                        ` : `
                                        <div class="text-center text-gray-500">Tidak ada pinjaman aktif</div>
                                        `}

                    <!-- Riwayat Pembayaran -->
                    <section>
                        <h4 class="font-semibold text-lg text-gray-800 mb-2">📝 Riwayat Pembayaran Terakhir</h4>
                        ${payments.length > 0 ? `
                                            <div class="space-y-2">
                                                ${payments.map(p => `
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded text-sm">
                                    <div>
                                        <span class="font-medium">${p.date}</span>
                                        <span class="text-gray-500 ml-2">(${p.method})</span>
                                    </div>
                                    <span class="font-semibold text-green-600">Rp ${p.amount.toLocaleString('id-ID')}</span>
                                </div>
                            `).join('')}
                                            </div>
                                            ` : `
                                            <div class="text-sm text-gray-500">Belum ada pembayaran</div>
                                            `}
                    </section>
                </div>
            `;
                })
                .catch(err => {
                    console.error(err);
                    content.innerHTML = `
                <div class="text-center py-4 text-red-500">Terjadi kesalahan saat memuat detail</div>
            `;
                });
        }


        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        function updateTaskStatus(taskId, status) {
            if (confirm('Apakah Anda yakin ingin mengubah status tugas ini?')) {
                fetch(`/collector/task/${taskId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status tugas berhasil diperbarui!');
                            location.reload(); // Refresh halaman untuk update data
                        } else {
                            alert('Gagal mengubah status tugas: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengubah status tugas');
                    });
            }
        }

        // Auto refresh data every 5 minutes
        setInterval(() => {
            location.reload();
        }, 300000);

        // Close modals when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        document.getElementById('paymentFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewDiv = document.getElementById('filePreview');
            const previewContent = document.getElementById('previewContent');

            if (file) {
                // Validasi ukuran file (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    e.target.value = '';
                    previewDiv.classList.add('hidden');
                    return;
                }

                // Tampilkan preview
                previewDiv.classList.remove('hidden');

                if (file.type.startsWith('image/')) {
                    // Preview untuk gambar
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContent.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="max-w-full h-32 object-cover rounded">
                    <p class="text-xs text-gray-500 mt-1">${file.name}</p>
                `;
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    // Preview untuk PDF
                    previewContent.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 18h12V6l-4-4H4v16z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium">${file.name}</p>
                        <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                </div>
            `;
                }
            } else {
                previewDiv.classList.add('hidden');
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add any initialization code here
            console.log('Collector Dashboard loaded');
        });
    </script>
</x-user-layout>
