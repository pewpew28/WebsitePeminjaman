<x-admin-layout title="Create Loan">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Loan</h1>

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.loans.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <!-- Basic Loan Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Basic Loan Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label for="nasabah_search" class="block text-sm font-medium text-gray-700">Nasabah <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="nasabah_search" placeholder="Cari nama nasabah..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nasabah_id') border-red-500 @enderror"
                            autocomplete="off">

                        <!-- Hidden select for form submission -->
                        <select name="nasabah_id" id="nasabah_id" class="hidden">
                            <option value="">Select Nasabah</option>
                            @foreach (\App\Models\Nasabah::whereDoesntHave('loans', function ($query) {
        $query->where('status', 'active');
    })->get() as $nasabah)
                                <option value="{{ $nasabah->id }}"
                                    {{ old('nasabah_id') == $nasabah->id ? 'selected' : '' }}>{{ $nasabah->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Dropdown suggestions -->
                        <div id="nasabah_dropdown"
                            class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <!-- Options will be populated by JavaScript -->
                        </div>

                        @error('nasabah_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="loan_amount" class="block text-sm font-medium text-gray-700">Loan Amount <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="loan_amount" id="loan_amount" value="{{ old('loan_amount') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('loan_amount') border-red-500 @enderror"
                            oninput="calculateLoan()">
                        @error('loan_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%)
                            <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="40"
                            readonly
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('interest_rate') border-red-500 @enderror"
                            oninput="calculateLoan()">
                        @error('interest_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="loan_term" class="block text-sm font-medium text-gray-700">Loan Term <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="loan_term" id="loan_term" value="{{ old('loan_term') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('loan_term') border-red-500 @enderror"
                            oninput="calculateLoan()">
                        @error('loan_term')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="term_unit" class="block text-sm font-medium text-gray-700">Term Unit <span
                                class="text-red-500">*</span></label>
                        <select name="term_unit" id="term_unit"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('term_unit') border-red-500 @enderror"
                            onchange="calculateLoan()">
                            <option value="daily" {{ old('term_unit', 'daily') == 'daily' ? 'selected' : '' }}>Daily
                            </option>
                            <option value="weekly" {{ old('term_unit') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('term_unit') == 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                        </select>
                        @error('term_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Date Information (Auto-calculated) -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Date Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', date('Y-m-d')) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror bg-gray-50"
                            readonly>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="disbursement_date" class="block text-sm font-medium text-gray-700">Disbursement
                            Date</label>
                        <input type="date" name="disbursement_date" id="disbursement_date"
                            value="{{ old('disbursement_date', date('Y-m-d')) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('disbursement_date') border-red-500 @enderror bg-gray-50"
                            readonly>
                        @error('disbursement_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror bg-gray-50"
                            readonly>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Loan Calculation Summary -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Loan Calculation Summary</h2>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Total Interest Amount</p>
                            <p class="text-lg font-semibold text-blue-600" id="interest_amount_display">Rp 0</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Amount Disbursed to Customer</p>
                            <p class="text-lg font-semibold text-green-600" id="disbursed_amount_display">Rp 0</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Amount Customer Must Repay</p>
                            <p class="text-lg font-semibold text-red-600" id="repay_amount_display">Rp 0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fine Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Fine Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fine_rate" class="block text-sm font-medium text-gray-700">Fine Rate (%)</label>
                        <input type="number" step="0.01" name="fine_rate" id="fine_rate"
                            value="{{ old('fine_rate', 5) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fine_rate') border-red-500 @enderror">
                        @error('fine_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fine_unit" class="block text-sm font-medium text-gray-700">Fine Unit</label>
                        <select name="fine_unit" id="fine_unit"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fine_unit') border-red-500 @enderror">
                            <option value="percentage"
                                {{ old('fine_unit', 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage
                            </option>
                            <option value="fixed" {{ old('fine_unit') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                        @error('fine_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Approval Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Approval Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="approved_by" class="block text-sm font-medium text-gray-700">Approved By</label>
                        <select name="approved_by" id="approved_by"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('approved_by') border-red-500 @enderror">
                            <option value="">Select Approver</option>
                            @foreach (\App\Models\User::whereIn('role', ['admin', 'finance'])->get() as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('approved_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('approved_by')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Tracking (Auto-calculated, Hidden) -->
            <input type="hidden" name="total_principal_paid" id="total_principal_paid" value="0">
            <input type="hidden" name="total_interest_paid" id="total_interest_paid" value="0">
            <input type="hidden" name="total_fines_paid" id="total_fines_paid" value="0">
            <input type="hidden" name="total_amount_with_interest" id="total_amount_with_interest" value="0">
            <input type="hidden" name="remaining_principal" id="remaining_principal" value="0">
            <input type="hidden" name="remaining_interest" id="remaining_interest" value="0">
            <input type="hidden" name="remaining_fines" id="remaining_fines" value="0">

            <!-- Refinancing Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Refinancing Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="is_refinanced" class="flex items-center">
                            <input type="checkbox" name="is_refinanced" id="is_refinanced" value="1"
                                {{ old('is_refinanced') ? 'checked' : '' }}
                                class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-700">Is Refinanced</span>
                        </label>
                        @error('is_refinanced')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="original_loan_id" class="block text-sm font-medium text-gray-700">Original
                            Loan</label>
                        <select name="original_loan_id" id="original_loan_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('original_loan_id') border-red-500 @enderror">
                            <option value="">Select Original Loan</option>
                            @foreach (\App\Models\Loan::all() as $loan)
                                <option value="{{ $loan->id }}"
                                    {{ old('original_loan_id') == $loan->id ? 'selected' : '' }}>
                                    Loan #{{ $loan->id }} - {{ $loan->nasabah->name ?? 'Unknown' }}
                                </option>
                            @endforeach
                        </select>
                        @error('original_loan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t pt-6">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Create Loan
                </button>
                <a href="{{ route('admin.loans.index') }}"
                    class="ml-4 text-gray-600 hover:text-gray-800 px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Initialize nasabah options array
        let nasabahOptions = [];

        // Set today's date on page load
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').value = today;
            document.getElementById('disbursement_date').value = today;

            // Initialize nasabah options from select element
            const nasabahSelect = document.getElementById('nasabah_id');
            nasabahOptions = Array.from(nasabahSelect.options).map(option => ({
                value: option.value,
                text: option.textContent
            })).filter(option => option.value !== '');

            // Set up search functionality
            setupNasabahSearch();

            // Auto-select nasabah from URL parameter
            autoSelectNasabahFromURL();

            calculateLoan();
        });

        function autoSelectNasabahFromURL() {
            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const nasabahId = urlParams.get('nasabah_id');

            if (nasabahId) {
                // Find the nasabah option with matching ID
                const selectedNasabah = nasabahOptions.find(option => option.value === nasabahId);

                if (selectedNasabah) {
                    // Set the hidden select value
                    document.getElementById('nasabah_id').value = selectedNasabah.value;

                    // Set the search input value to show the selected nasabah name
                    document.getElementById('nasabah_search').value = selectedNasabah.text;

                    // Optional: Add visual feedback that nasabah was auto-selected
                    const searchInput = document.getElementById('nasabah_search');
                    searchInput.style.backgroundColor = '#dcfce7'; // Light green background
                    searchInput.style.borderColor = '#22c55e'; // Green border

                    // Remove the visual feedback after 2 seconds
                    setTimeout(() => {
                        searchInput.style.backgroundColor = '';
                        searchInput.style.borderColor = '';
                    }, 2000);
                }
            }
        }

        function setupNasabahSearch() {
            const searchInput = document.getElementById('nasabah_search');
            const dropdown = document.getElementById('nasabah_dropdown');
            const hiddenSelect = document.getElementById('nasabah_id');

            // Show dropdown when input is focused
            searchInput.addEventListener('focus', function() {
                showDropdown();
            });

            // Filter options as user types
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filterNasabahOptions(searchTerm);
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('#nasabah_search') && !event.target.closest('#nasabah_dropdown')) {
                    dropdown.classList.add('hidden');
                }
            });

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', function(event) {
                const options = dropdown.querySelectorAll('.dropdown-option');
                const activeOption = dropdown.querySelector('.dropdown-option.active');

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    if (activeOption) {
                        activeOption.classList.remove('active');
                        const nextOption = activeOption.nextElementSibling;
                        if (nextOption) {
                            nextOption.classList.add('active');
                        } else {
                            options[0]?.classList.add('active');
                        }
                    } else {
                        options[0]?.classList.add('active');
                    }
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (activeOption) {
                        activeOption.classList.remove('active');
                        const prevOption = activeOption.previousElementSibling;
                        if (prevOption) {
                            prevOption.classList.add('active');
                        } else {
                            options[options.length - 1]?.classList.add('active');
                        }
                    } else {
                        options[options.length - 1]?.classList.add('active');
                    }
                } else if (event.key === 'Enter') {
                    event.preventDefault();
                    if (activeOption) {
                        selectNasabah(activeOption.dataset.value, activeOption.textContent);
                    }
                } else if (event.key === 'Escape') {
                    dropdown.classList.add('hidden');
                }
            });
        }

        function showDropdown() {
            filterNasabahOptions('');
        }

        function filterNasabahOptions(searchTerm) {
            const dropdown = document.getElementById('nasabah_dropdown');

            const filteredOptions = nasabahOptions.filter(option =>
                option.text.toLowerCase().includes(searchTerm)
            );

            dropdown.innerHTML = '';

            if (filteredOptions.length === 0) {
                dropdown.innerHTML = '<div class="px-4 py-2 text-gray-500">Tidak ada nasabah ditemukan</div>';
            } else {
                filteredOptions.forEach(option => {
                    const optionElement = document.createElement('div');
                    optionElement.className =
                        'dropdown-option px-4 py-2 cursor-pointer hover:bg-blue-50 hover:text-blue-600';
                    optionElement.textContent = option.text;
                    optionElement.dataset.value = option.value;

                    optionElement.addEventListener('click', function() {
                        selectNasabah(option.value, option.text);
                    });

                    dropdown.appendChild(optionElement);
                });
            }

            dropdown.classList.remove('hidden');
        }

        function selectNasabah(value, text) {
            document.getElementById('nasabah_search').value = text;
            document.getElementById('nasabah_id').value = value;
            document.getElementById('nasabah_dropdown').classList.add('hidden');

            // Remove active class from all options
            const options = document.querySelectorAll('.dropdown-option');
            options.forEach(option => option.classList.remove('active'));
        }

        function calculateLoan() {
            const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
            const interestRate = parseFloat(document.getElementById('interest_rate').value) || 0;
            const loanTerm = parseInt(document.getElementById('loan_term').value) || 0;
            const termUnit = document.getElementById('term_unit').value;

            if (loanAmount > 0 && interestRate > 0 && loanTerm > 0) {
                // Calculate interest amount (flat rate)
                const interestAmount = (loanAmount * interestRate / 100);

                // Calculate total amount with interest
                const totalAmountWithInterest = loanAmount + interestAmount;

                // Amount disbursed to customer (loan amount minus interest paid upfront)
                const disbursedAmount = loanAmount - interestAmount;

                // Amount customer must repay (only principal, since interest paid upfront)
                const repayAmount = loanAmount;

                // Calculate end date based on term unit
                const startDate = new Date(document.getElementById('start_date').value);
                const endDate = new Date(startDate);

                let daysToAdd = 0;
                switch (termUnit) {
                    case 'daily':
                        daysToAdd = loanTerm;
                        break;
                    case 'weekly':
                        daysToAdd = loanTerm * 7;
                        break;
                    case 'monthly':
                        // Add months directly instead of converting to days to handle varying month lengths
                        endDate.setMonth(startDate.getMonth() + loanTerm);
                        break;
                }

                if (termUnit !== 'monthly') {
                    endDate.setDate(startDate.getDate() + daysToAdd);
                }

                document.getElementById('end_date').value = endDate.toISOString().split('T')[0];

                // Update display values
                document.getElementById('interest_amount_display').textContent = 'Rp ' + interestAmount.toLocaleString(
                    'id-ID');
                document.getElementById('disbursed_amount_display').textContent = 'Rp ' + disbursedAmount.toLocaleString(
                    'id-ID');
                document.getElementById('repay_amount_display').textContent = 'Rp ' + repayAmount.toLocaleString('id-ID');

                // Update hidden fields for payment tracking
                document.getElementById('total_interest_paid').value = interestAmount; // Interest paid upfront
                document.getElementById('total_amount_with_interest').value = totalAmountWithInterest;
                document.getElementById('remaining_principal').value = loanAmount; // Full principal amount remaining
                document.getElementById('remaining_interest').value = 0; // No interest remaining (paid upfront)
                document.getElementById('remaining_fines').value = 0;
            } else {
                // Reset displays if inputs are invalid
                document.getElementById('interest_amount_display').textContent = 'Rp 0';
                document.getElementById('disbursed_amount_display').textContent = 'Rp 0';
                document.getElementById('repay_amount_display').textContent = 'Rp 0';
            }
        }

        // Add event listeners to recalculate when start date changes
        document.getElementById('start_date').addEventListener('change', calculateLoan);
    </script>

    <style>
        .dropdown-option.active {
            background-color: #dbeafe;
            color: #2563eb;
        }
    </style>
</x-admin-layout>
