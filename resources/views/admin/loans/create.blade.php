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
                    <div>
                        <label for="nasabah_id" class="block text-sm font-medium text-gray-700">Nasabah <span
                                class="text-red-500">*</span></label>
                        <select name="nasabah_id" id="nasabah_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nasabah_id') border-red-500 @enderror">
                            <option value="">Select Nasabah</option>
                            @foreach (\App\Models\Nasabah::whereDoesntHave('loans', function ($query) {
                                $query->where('status', 'active');
                            })->get() as $nasabah)
                                <option value="{{ $nasabah->id }}"
                                    {{ old('nasabah_id') == $nasabah->id ? 'selected' : '' }}>{{ $nasabah->name }}
                                </option>
                            @endforeach
                        </select>
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
                        <input type="number" step="0.01" name="interest_rate" id="interest_rate"
                            value="{{ old('interest_rate') }}"
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
        // Set today's date on page load
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').value = today;
            document.getElementById('disbursement_date').value = today;
            calculateLoan();
        });

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
</x-admin-layout>
