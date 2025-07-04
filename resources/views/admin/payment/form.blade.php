<x-admin-layout title="Pembayaran Cicilan">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pembayaran Cicilan</h1>
                <p class="mt-2 text-sm text-gray-600">Pilih nasabah dan cicilan yang akan dibayar</p>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Payment Form -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <form action="{{ route('admin.payment.store') }}" method="POST" id="paymentForm">
                @csrf

                <!-- Step 1: Pilih Nasabah -->
                <div class="bg-blue-50 px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-blue-900 flex items-center">
                        <span
                            class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">1</span>
                        Pilih Nasabah
                    </h2>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <label for="nasabah_search" class="block text-sm font-medium text-gray-700 mb-2">
                            Cari Nasabah
                        </label>
                        <div class="relative">
                            <input type="text" id="nasabah_search" placeholder="Ketik nama nasabah untuk mencari..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10"
                                autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="nasabah_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Nasabah
                        </label>
                        <select id="nasabah_id" name="nasabah_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            onchange="updateLoans()">
                            <option value="">-- Pilih Nasabah --</option>
                            @foreach ($nasabahs as $nasabah)
                                <option value="{{ $nasabah->id }}" data-loans='@json($nasabah->loans)'>
                                    {{ $nasabah->user ? $nasabah->user->name : 'N/A' }}
                                    @if ($nasabah->loans && $nasabah->loans->count() > 0)
                                        - ({{ $nasabah->loans->count() }} Pinjaman Aktif)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('nasabah_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Step 2: Pilih Pinjaman -->
                <div class="bg-green-50 px-6 py-4 border-b" id="loanStepHeader" style="display: none;">
                    <h2 class="text-lg font-semibold text-green-900 flex items-center">
                        <span
                            class="bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">2</span>
                        Pilih Pinjaman
                    </h2>
                </div>

                <div class="p-6" id="loanSection" style="display: none;">
                    <div>
                        <label for="loan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Pinjaman yang Akan Dibayar
                        </label>
                        <select id="loan_id" name="loan_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            onchange="updateInstallments()" disabled>
                            <option value="">-- Pilih Pinjaman --</option>
                        </select>
                        @error('loan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Step 3: Pilih Cicilan -->
                <div class="bg-purple-50 px-6 py-4 border-b" id="installmentStepHeader" style="display: none;">
                    <h2 class="text-lg font-semibold text-purple-900 flex items-center">
                        <span
                            class="bg-purple-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">3</span>
                        Pilih Cicilan yang Akan Dibayar
                    </h2>
                </div>

                <div class="p-6" id="installmentsSection" style="display: none;">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="mr-2">
                            <label for="selectAll" class="text-sm font-medium text-gray-700">Pilih Semua Cicilan</label>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span id="selectedCount">0</span> cicilan dipilih
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pilih
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cicilan Ke-
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jatuh Tempo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Detail Pembayaran
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="installmentsBody">
                                <!-- Installments will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    @error('installment_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Step 4: Konfirmasi Pembayaran -->
                <div class="bg-orange-50 px-6 py-4 border-b" id="paymentStepHeader" style="display: none;">
                    <h2 class="text-lg font-semibold text-orange-900 flex items-center">
                        <span
                            class="bg-orange-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm mr-3">4</span>
                        Konfirmasi Pembayaran
                    </h2>
                </div>

                <div class="p-6" id="paymentSection" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Sisa Pembayaran yang Dipilih
                            </label>
                            <div class="text-2xl font-bold text-gray-900" id="total_amount_display">
                                Rp 0
                            </div>
                            <input type="hidden" id="total_amount" name="total_amount" value="0">
                        </div>

                        <div>
                            <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Pembayaran
                            </label>
                            <input type="number" id="payment_amount" name="payment_amount" step="0.01"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Masukkan jumlah pembayaran" oninput="validatePayment()">
                            @error('payment_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="payment_warning" class="mt-2 text-sm text-amber-600" style="display: none;">
                                ⚠️ Jumlah pembayaran tidak sama dengan total sisa pembayaran
                            </div>
                        </div>
                    </div>

                    <!-- Summary Information -->
                    <div class="mt-6 bg-blue-50 p-4 rounded-lg" id="paymentSummary" style="display: none;">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Ringkasan Pembayaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="summaryContent">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" id="submitButton"
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Proses Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Dynamic Behavior -->
    <script>
        let loansData = {};
        let allNasabahs = [];
        let urlParams = {};

        // Initialize search functionality and URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            const nasabahSelect = document.getElementById('nasabah_id');
            const searchInput = document.getElementById('nasabah_search');

            // Store all options
            allNasabahs = Array.from(nasabahSelect.options).slice(1); // Skip first option

            // Parse URL parameters
            urlParams = getUrlParams();

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                // Clear current options (keep first option)
                nasabahSelect.innerHTML = '<option value="">-- Pilih Nasabah --</option>';

                // Filter and add matching options
                allNasabahs.forEach(option => {
                    if (option.textContent.toLowerCase().includes(searchTerm)) {
                        nasabahSelect.appendChild(option.cloneNode(true));
                    }
                });
            });

            // Auto-select based on URL parameters
            if (urlParams.nasabah_id) {
                autoSelectFromUrl();
            }
        });

        function getUrlParams() {
            const params = {};
            const urlSearchParams = new URLSearchParams(window.location.search);

            params.nasabah_id = urlSearchParams.get('nasabah_id');
            params.loan_id = urlSearchParams.get('loan_id');
            params.installment_id = urlSearchParams.get('installment_id');

            return params;
        }

        function autoSelectFromUrl() {
            const nasabahSelect = document.getElementById('nasabah_id');

            // Auto-select nasabah
            if (urlParams.nasabah_id) {
                nasabahSelect.value = urlParams.nasabah_id;

                // Trigger change event to load loans
                updateLoans();

                // Wait for DOM to update, then select loan
                setTimeout(() => {
                    if (urlParams.loan_id) {
                        const loanSelect = document.getElementById('loan_id');
                        loanSelect.value = urlParams.loan_id;

                        // Trigger change event to load installments
                        updateInstallments();

                        // Wait for installments to load, then select installment
                        setTimeout(() => {
                            if (urlParams.installment_id) {
                                const installmentCheckbox = document.querySelector(
                                    `input[name="installment_ids[]"][value="${urlParams.installment_id}"]`
                                    );
                                if (installmentCheckbox && !installmentCheckbox.disabled) {
                                    installmentCheckbox.checked = true;
                                    updateTotalAmount();
                                }
                            }
                        }, 100);
                    }
                }, 100);
            }
        }

        function updateLoans() {
            const nasabahSelect = document.getElementById('nasabah_id');
            const loanSelect = document.getElementById('loan_id');
            const loanSection = document.getElementById('loanSection');
            const loanStepHeader = document.getElementById('loanStepHeader');

            // Hide downstream sections
            hideInstallmentSection();
            hidePaymentSection();

            // Reset loan select
            loanSelect.innerHTML = '<option value="">-- Pilih Pinjaman --</option>';
            loanSelect.disabled = true;

            const selectedOption = nasabahSelect.options[nasabahSelect.selectedIndex];
            if (selectedOption.value) {
                loansData = JSON.parse(selectedOption.getAttribute('data-loans'));

                if (loansData.length > 0) {
                    loansData.forEach(loan => {
                        const option = document.createElement('option');
                        option.value = loan.id;
                        option.textContent =
                            `Pinjaman #${loan.id} - Rp ${new Intl.NumberFormat('id-ID').format(loan.loan_amount)}`;
                        loanSelect.appendChild(option);
                    });

                    loanSelect.disabled = false;
                    loanStepHeader.style.display = 'block';
                    loanSection.style.display = 'block';
                } else {
                    alert('Nasabah ini tidak memiliki pinjaman aktif');
                }
            } else {
                loanStepHeader.style.display = 'none';
                loanSection.style.display = 'none';
            }
        }

        function updateInstallments() {
            const loanSelect = document.getElementById('loan_id');
            const installmentStepHeader = document.getElementById('installmentStepHeader');
            const installmentsSection = document.getElementById('installmentsSection');
            const installmentsBody = document.getElementById('installmentsBody');

            // Hide payment section
            hidePaymentSection();

            // Reset installment section
            installmentsBody.innerHTML = '';
            document.getElementById('selectAll').checked = false;
            updateSelectedCount();

            if (loanSelect.value) {
                const selectedLoan = loansData.find(loan => loan.id == loanSelect.value);

                if (selectedLoan && selectedLoan.installments && selectedLoan.installments.length > 0) {
                    const unpaidInstallments = selectedLoan.installments.filter(inst => inst.status !== 'paid');

                    if (unpaidInstallments.length > 0) {
                        selectedLoan.installments.forEach((installment, index) => {
                            const row = document.createElement('tr');
                            row.className = installment.status === 'paid' ? 'bg-gray-50' : 'hover:bg-gray-50';

                            const isDisabled = installment.status === 'paid';
                            const statusClass = installment.status === 'paid' ? 'bg-green-100 text-green-800' :
                                'bg-yellow-100 text-yellow-800';

                            // Calculate remaining amount (use remaining_amount if available, otherwise calculate)
                            const remainingAmount = installment.remaining_amount || (installment.total_due_amount -
                                (installment.amount_paid || 0));
                            const totalDueAmount = installment.total_due_amount;
                            const amountPaid = installment.amount_paid || 0;

                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input 
                                        type="checkbox" 
                                        name="installment_ids[]" 
                                        value="${installment.id}" 
                                        class="installment-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                        ${isDisabled ? 'disabled' : ''} 
                                        onchange="updateTotalAmount()"
                                        data-amount="${remainingAmount}"
                                        data-total-due="${totalDueAmount}"
                                        data-amount-paid="${amountPaid}"
                                        data-installment-number="${index + 1}"
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    ${index + 1}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    ${new Date(installment.due_date).toLocaleDateString('id-ID')}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">
                                            Total: Rp ${new Intl.NumberFormat('id-ID').format(totalDueAmount)}
                                        </div>
                                        ${amountPaid > 0 ? `
                                                <div class="text-gray-500">
                                                    Dibayar: Rp ${new Intl.NumberFormat('id-ID').format(amountPaid)}
                                                </div>
                                                <div class="font-medium text-blue-600">
                                                    Sisa: Rp ${new Intl.NumberFormat('id-ID').format(remainingAmount)}
                                                </div>
                                            ` : `
                                                <div class="font-medium text-blue-600">
                                                    Belum dibayar: Rp ${new Intl.NumberFormat('id-ID').format(remainingAmount)}
                                                </div>
                                            `}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                        ${installment.status === 'paid' ? 'Lunas' : 'Belum Lunas'}
                                    </span>
                                </td>
                            `;
                            installmentsBody.appendChild(row);
                        });

                        installmentStepHeader.style.display = 'block';
                        installmentsSection.style.display = 'block';
                    } else {
                        alert('Semua cicilan untuk pinjaman ini sudah lunas');
                        hideInstallmentSection();
                    }
                } else {
                    alert('Pinjaman ini tidak memiliki data cicilan');
                    hideInstallmentSection();
                }
            } else {
                hideInstallmentSection();
            }
        }

        function updateTotalAmount() {
            const checkboxes = document.querySelectorAll('.installment-checkbox:checked');
            let total = 0;

            checkboxes.forEach(checkbox => {
                // Use remaining amount instead of total_due_amount
                total += parseFloat(checkbox.getAttribute('data-amount'));
            });

            document.getElementById('total_amount').value = total;
            document.getElementById('total_amount_display').textContent =
                `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;

            updateSelectedCount();
            updatePaymentSummary();

            // Show payment section if installments are selected
            if (checkboxes.length > 0) {
                showPaymentSection();
            } else {
                hidePaymentSection();
            }

            // Update payment amount field with remaining amount
            document.getElementById('payment_amount').value = total;
            validatePayment();
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.installment-checkbox:checked');
            document.getElementById('selectedCount').textContent = checkboxes.length;
        }

        function updatePaymentSummary() {
            const checkboxes = document.querySelectorAll('.installment-checkbox:checked');
            const summaryContent = document.getElementById('summaryContent');
            const paymentSummary = document.getElementById('paymentSummary');

            if (checkboxes.length > 0) {
                let totalDue = 0;
                let totalPaid = 0;
                let totalRemaining = 0;

                checkboxes.forEach(checkbox => {
                    totalDue += parseFloat(checkbox.getAttribute('data-total-due'));
                    totalPaid += parseFloat(checkbox.getAttribute('data-amount-paid'));
                    totalRemaining += parseFloat(checkbox.getAttribute('data-amount'));
                });

                summaryContent.innerHTML = `
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            Rp ${new Intl.NumberFormat('id-ID').format(totalDue)}
                        </div>
                        <div class="text-sm text-gray-600">Total Kewajiban</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            Rp ${new Intl.NumberFormat('id-ID').format(totalPaid)}
                        </div>
                        <div class="text-sm text-gray-600">Sudah Dibayar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            Rp ${new Intl.NumberFormat('id-ID').format(totalRemaining)}
                        </div>
                        <div class="text-sm text-gray-600">Sisa Pembayaran</div>
                    </div>
                `;

                paymentSummary.style.display = 'block';
            } else {
                paymentSummary.style.display = 'none';
            }
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.installment-checkbox:not(:disabled)');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            updateTotalAmount();
        }

        function showPaymentSection() {
            document.getElementById('paymentStepHeader').style.display = 'block';
            document.getElementById('paymentSection').style.display = 'block';
        }

        function hidePaymentSection() {
            document.getElementById('paymentStepHeader').style.display = 'none';
            document.getElementById('paymentSection').style.display = 'none';
        }

        function hideInstallmentSection() {
            document.getElementById('installmentStepHeader').style.display = 'none';
            document.getElementById('installmentsSection').style.display = 'none';
        }

        function validatePayment() {
            const paymentAmount = parseFloat(document.getElementById('payment_amount').value) || 0;
            const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
            const warning = document.getElementById('payment_warning');
            const submitButton = document.getElementById('submitButton');

            if (paymentAmount > 0 && totalAmount > 0) {
                if (paymentAmount !== totalAmount) {
                    warning.style.display = 'block';
                } else {
                    warning.style.display = 'none';
                }
                submitButton.disabled = false;
            } else {
                warning.style.display = 'none';
                submitButton.disabled = true;
            }
        }

        // Form validation before submit
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const selectedInstallments = document.querySelectorAll('.installment-checkbox:checked');

            if (selectedInstallments.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu cicilan untuk dibayar');
                return false;
            }

            const paymentAmount = parseFloat(document.getElementById('payment_amount').value);
            if (!paymentAmount || paymentAmount <= 0) {
                e.preventDefault();
                alert('Silakan masukkan jumlah pembayaran yang valid');
                return false;
            }

            // Create summary for confirmation
            const totalRemaining = parseFloat(document.getElementById('total_amount').value);
            const installmentCount = selectedInstallments.length;

            const confirmMessage = `Konfirmasi Pembayaran:
- Jumlah cicilan: ${installmentCount}
- Total sisa pembayaran: Rp ${new Intl.NumberFormat('id-ID').format(totalRemaining)}
- Jumlah yang akan dibayar: Rp ${new Intl.NumberFormat('id-ID').format(paymentAmount)}

Apakah Anda yakin ingin memproses pembayaran ini?`;

            return confirm(confirmMessage);
        });
    </script>
</x-admin-layout>
