<x-admin-layout title="Edit Loan">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Loan</h1>

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nasabah_id" class="block text-sm font-medium text-gray-700">Nasabah</label>
                    <select name="nasabah_id" id="nasabah_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('nasabah_id') border-red-500 @enderror">
                        <option value="">Select Nasabah</option>
                        @foreach (\App\Models\Nasabah::all() as $nasabah)
                            <option value="{{ $nasabah->id }}" {{ old('nasabah_id', $loan->nasabah_id) == $nasabah->id ? 'selected' : '' }}>{{ $nasabah->name }}</option>
                        @endforeach
                    </select>
                    @error('nasabah_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="loan_amount" class="block text-sm font-medium text-gray-700">Loan Amount</label>
                    <input type="number" name="loan_amount" id="loan_amount" value="{{ old('loan_amount', $loan->loan_amount) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('loan_amount') border-red-500 @enderror">
                    @error('loan_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%)</label>
                    <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="{{ old('interest_rate', $loan->interest_rate) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('interest_rate') border-red-500 @enderror">
                    @error('interest_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="loan_term" class="block text-sm font-medium text-gray-700">Loan Term</label>
                    <input type="number" name="loan_term" id="loan_term" value="{{ old('loan_term', $loan->loan_term) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('loan_term') border-red-500 @enderror">
                    @error('loan_term')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="term_unit" class="block text-sm font-medium text-gray-700">Term Unit</label>
                    <select name="term_unit" id="term_unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('term_unit') border-red-500 @enderror">
                        <option value="days" {{ old('term_unit', $loan->term_unit) == 'days' ? 'selected' : '' }}>Days</option>
                        <option value="months" {{ old('term_unit', $loan->term_unit) == 'months' ? 'selected' : '' }}>Months</option>
                    </select>
                    @error('term_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $loan->start_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', $loan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="active" {{ old('status', $loan->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $loan->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="fine_rate" class="block text-sm font-medium text-gray-700">Fine Rate (%)</label>
                    <input type="number" step="0.01" name="fine_rate" id="fine_rate" value="{{ old('fine_rate', $loan->fine_rate) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fine_rate') border-red-500 @enderror">
                    @error('fine_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="fine_unit" class="block text-sm font-medium text-gray-700">Fine Unit</label>
                    <select name="fine_unit" id="fine_unit" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fine_unit') border-red-500 @enderror">
                        <option value="percentage" {{ old('fine_unit', $loan->fine_unit) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('fine_unit', $loan->fine_unit) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                    </select>
                    @error('fine_unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Update Loan
                </button>
                <a href="{{ route('admin.loans.index') }}" class="ml-4 text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>