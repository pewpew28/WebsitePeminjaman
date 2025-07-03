<x-admin-layout title="View Loan">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Loan Details</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">ID</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->id }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nasabah</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->nasabah ? $loan->nasabah->name : 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Loan Amount</h3>
                    <p class="mt-1 text-gray-900">{{ number_format($loan->loan_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Interest Rate</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->interest_rate }}%</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Loan Term</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->loan_term }} {{ $loan->term_unit }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Start Date</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->start_date->format('Y-m-d') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">End Date</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->end_date->format('Y-m-d') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->status }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Approved By</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->approved_by ? \App\Models\User::find($loan->approved_by)->name : 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Disbursement Date</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : 'N/A' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Fine Rate</h3>
                    <p class="mt-1 text-gray-900">{{ $loan->fine_rate }} {{ $loan->fine_unit }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Total Amount with Interest</h3>
                    <p class="mt-1 text-gray-900">{{ number_format($loan->total_amount_with_interest, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.loans.edit', $loan->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Edit Loan
                </a>
                <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this loan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ml-4 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete Loan
                    </button>
                </form>
                @if ($loan->status == 'pending')
                    <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to approve this loan?');">
                        @csrf
                        <button type="submit" class="ml-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Approve Loan
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.loans.index') }}" class="ml-4 text-gray-600 hover:text-gray-800">
                    Back to Loans
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>