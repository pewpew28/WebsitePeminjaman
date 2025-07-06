<x-admin-layout title="View Loan">
    <div class="max-w-6xl mx-auto p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Loan #{{ $loan->id }}</h1>
                <p class="text-gray-600">{{ $loan->nasabah ? $loan->nasabah->name : 'N/A' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn-primary">Edit</a>
                @if ($loan->status == 'pending')
                    <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-success"
                            onclick="return confirm('Approve this loan?')">Approve</button>
                    </form>
                @endif
                <a href="{{ route('admin.loans.index') }}" class="btn-secondary">Back</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Loan Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="card bg-blue-50 border-blue-200">
                <div class="p-4">
                    <h3 class="text-sm font-medium text-blue-600">Loan Amount</h3>
                    <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <div class="card bg-green-50 border-green-200">
                <div class="p-4">
                    <h3 class="text-sm font-medium text-green-600">Interest Rate</h3>
                    <p class="text-2xl font-bold text-green-900">{{ $loan->interest_rate }}%</p>
                </div>
            </div>
            <div class="card bg-purple-50 border-purple-200">
                <div class="p-4">
                    <h3 class="text-sm font-medium text-purple-600">Term</h3>
                    <p class="text-2xl font-bold text-purple-900">{{ $loan->loan_term }} {{ $loan->term_unit }}</p>
                </div>
            </div>
            <div class="card bg-gray-50 border-gray-200">
                <div class="p-4">
                    <h3 class="text-sm font-medium text-gray-600">Status</h3>
                    <span class="status-badge status-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Loan Details -->
        <div class="card mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">Loan Details</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Start Date:</span>
                        <span class="font-medium ml-2">{{ $loan->start_date->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">End Date:</span>
                        <span class="font-medium ml-2">{{ $loan->end_date->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Fine Rate:</span>
                        <span class="font-medium ml-2">{{ $loan->fine_rate }} {{ $loan->fine_unit }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Total Amount:</span>
                        <span class="font-medium ml-2">Rp
                            {{ number_format($loan->total_amount_with_interest, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Approved By:</span>
                        <span
                            class="font-medium ml-2">{{ $loan->approved_by ? \App\Models\User::find($loan->approved_by)->name : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Disbursement:</span>
                        <span
                            class="font-medium ml-2">{{ $loan->disbursement_date ? $loan->disbursement_date->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installments Section -->
        <div class="card">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Payment Schedule</h2>
                    @if ($loan->installments->count() > 0)
                        <span class="text-sm text-gray-500">{{ $loan->installments->count() }} installments</span>
                    @endif
                </div>

                @if ($loan->installments->count() > 0)
                    <!-- Payment Summary -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-600">Total Due</p>
                            <p class="text-lg font-bold text-blue-900">Rp
                                {{ number_format($loan->installments->sum('total_due_amount'), 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-sm text-green-600">Paid</p>
                            <p class="text-lg font-bold text-green-900">Rp
                                {{ number_format($loan->installments->sum('amount_paid'), 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center p-3 bg-red-50 rounded-lg">
                            <p class="text-sm text-red-600">Outstanding</p>
                            <p class="text-lg font-bold text-red-900">Rp
                                {{ number_format($loan->installments->sum('total_due_amount') - $loan->installments->sum('amount_paid'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-lg">
                            <p class="text-sm text-orange-600">Overdue</p>
                            <p class="text-lg font-bold text-orange-900">
                                {{ $loan->installments->filter(fn($i) => $i->due_date < now() && $i->status !== 'paid')->count() }}
                            </p>
                        </div>
                    </div>

                    <!-- Installments List -->
                    <div class="space-y-3">
                        @foreach ($loan->installments->sortBy('installment_number') as $installment)
                            @php
                                $isOverdue = $installment->due_date < now() && $installment->status !== 'paid';
                                $statusClass = '';
                                $borderClass = '';

                                if ($installment->status === 'paid') {
                                    $statusClass = 'bg-green-50';
                                    $borderClass = 'border-green-200';
                                } elseif ($installment->status === 'partial') {
                                    $statusClass = 'bg-orange-50';
                                    $borderClass = 'border-orange-200';
                                } elseif ($isOverdue || $installment->status === 'overdue') {
                                    $statusClass = 'bg-red-50';
                                    $borderClass = 'border-red-200';
                                } else {
                                    $statusClass = 'bg-white';
                                    $borderClass = 'border-gray-200';
                                }
                            @endphp

                            <div
                                class="installment-card border rounded-lg p-4 hover:shadow-md transition-shadow {{ $statusClass }} {{ $borderClass }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span
                                                class="w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-medium">
                                                {{ $installment->installment_number }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-medium">
                                                @if ($installment->remaining_amount > 0 && $installment->remaining_amount < $installment->total_due_amount)
                                                    <!-- Jika ada remaining amount, coret total_due_amount dan tampilkan remaining -->
                                                    <span class="line-through text-gray-500">
                                                        Rp
                                                        {{ number_format($installment->total_due_amount, 0, ',', '.') }}
                                                    </span>
                                                    <span class="ml-2 text-lg font-bold text-red-600">
                                                        Rp
                                                        {{ number_format($installment->remaining_amount, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <!-- Jika tidak ada remaining amount atau remaining = total, tampilkan normal -->
                                                    <span
                                                        class="@if ($installment->status === 'paid') text-green-600 @endif">
                                                        Rp
                                                        {{ number_format($installment->total_due_amount, 0, ',', '.') }}
                                                    </span>
                                                @endif

                                                @if ($installment->fine_amount > 0)
                                                    <span class="text-red-600 text-sm">(+Rp
                                                        {{ number_format($installment->fine_amount, 0, ',', '.') }}
                                                        fine)</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                Due: {{ $installment->due_date->format('d M Y') }}
                                                @if ($installment->payment_date)
                                                    • Paid: {{ $installment->payment_date->format('d M Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="status-badge status-{{ $installment->status === 'overdue' || $isOverdue ? 'overdue' : $installment->status }}">
                                            @if ($isOverdue && $installment->status !== 'paid')
                                                Overdue
                                            @else
                                                {{ ucfirst($installment->status) }}
                                            @endif
                                        </span>
                                        @if ($installment->status != 'paid')
                                            <a href="{{ route('admin.payment.form', ['nasabah_id' => $loan->nasabah->id, 'loan_id' => $loan->id, 'installment_id' => $installment->id]) }}">
                                                <button class="btn-sm btn-primary">Pay</button>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if ($installment->amount_paid > 0)
                                    <div class="mt-2 text-sm">
                                        <span class="text-green-600 font-medium">
                                            Paid: Rp {{ number_format($installment->amount_paid, 0, ',', '.') }}
                                        </span>
                                        <span class="text-green-600 font-medium">
                                            Paid By {{ $installment->payer->name }}
                                        </span>
                                        @if ($installment->remaining_amount > 0)
                                            <span class="text-orange-600 font-medium ml-3">
                                                Remaining: Rp
                                                {{ number_format($installment->remaining_amount, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                @if ($installment->notes)
                                    <div class="mt-2 text-sm text-gray-600">
                                        <strong>Notes:</strong> {{ $installment->notes }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No installments</h3>
                        <p class="mt-1 text-sm text-gray-500">Installments will appear here once the loan is approved.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors;
        }

        .btn-success {
            @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors;
        }

        .btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors;
        }

        .btn-sm {
            @apply px-3 py-1 text-sm rounded-md font-medium transition-colors;
        }

        .card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200;
        }

        .alert {
            @apply p-4 rounded-lg mb-4;
        }

        .alert-success {
            @apply bg-green-100 text-green-700 border border-green-200;
        }

        .alert-error {
            @apply bg-red-100 text-red-700 border border-red-200;
        }

        .status-badge {
            @apply inline-flex px-2 py-1 text-xs font-semibold rounded-full;
        }

        .status-approved {
            @apply bg-green-100 text-green-800;
        }

        .status-pending {
            @apply bg-yellow-100 text-yellow-800;
        }

        .status-rejected {
            @apply bg-red-100 text-red-800;
        }

        .status-paid {
            @apply bg-green-100 text-green-800;
        }

        .status-overdue {
            @apply bg-red-100 text-red-800;
        }

        .status-partial {
            @apply bg-orange-100 text-orange-800;
        }

        /* Custom styling for strikethrough */
        .line-through {
            text-decoration: line-through;
            text-decoration-color: #9ca3af;
            text-decoration-thickness: 2px;
        }
    </style>
</x-admin-layout>
