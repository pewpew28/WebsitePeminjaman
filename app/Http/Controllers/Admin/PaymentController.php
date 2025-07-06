<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectorTask;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display payment form with customers who have active loans
     */
    public function index()
    {
        $nasabahs = Nasabah::with([
            'user',
            'loans' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('created_at', 'desc');
            },
            'loans.installments' => function ($query) {
                $query->where('status', '!=', 'paid')
                    ->orderBy('installment_number', 'asc');
            }
        ])->whereHas('loans', function ($query) {
            $query->where('status', 'active');
        })->orderBy('created_at', 'desc')->get();

        return view('admin.payment.index', compact('nasabahs'));
    }

    /**
     * Display payment form
     */
    public function form()
    {
        $nasabahs = Nasabah::with([
            'user',
            'loans' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('created_at', 'desc');
            },
            'loans.installments' => function ($query) {
                $query->where('status', '!=', 'paid')
                    ->orderBy('installment_number', 'asc');
            }
        ])->whereHas('loans', function ($query) {
            $query->where('status', 'active');
        })->orderBy('created_at', 'desc')->get();

        return view('admin.payment.form', compact('nasabahs'));
    }

    /**
     * Process payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'loan_id' => 'required|exists:loans,id',
            'installment_ids' => 'required|array|min:1',
            'installment_ids.*' => 'exists:installments,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:50',
        ]);

        // Validasi tambahan: pastikan loan belongs to nasabah
        $loan = Loan::with('nasabah')->find($validated['loan_id']);
        if ($loan->nasabah_id != $validated['nasabah_id']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pinjaman tidak sesuai dengan nasabah yang dipilih');
        }

        DB::beginTransaction();

        try {
            // Ambil installments yang akan diproses
            $installments = Installment::whereIn('id', $validated['installment_ids'])
                ->where('loan_id', $validated['loan_id'])
                ->where('status', '!=', 'paid')
                ->orderBy('installment_number', 'asc')
                ->lockForUpdate() // Prevent race conditions
                ->get();

            // Validasi installments
            if ($installments->count() !== count($validated['installment_ids'])) {
                throw new \Exception('Beberapa cicilan tidak valid atau sudah dibayar');
            }

            // Validasi bahwa total remaining amount sesuai dengan payment amount
            $totalRemaining = $this->calculateTotalRemaining($installments);
            if ($validated['payment_amount'] > $totalRemaining) {
                throw new \Exception('Jumlah pembayaran melebihi total yang harus dibayar');
            }

            // Proses pembayaran dengan user yang login
            $paymentResult = $this->processPaymentImproved($installments, $validated);

            // Update loan statistics
            $this->updateLoanStatistics($validated['loan_id']);

            // Log payment transaction
            $this->logPaymentTransaction($validated['loan_id'], $paymentResult, $validated);

            // Update CollectorTask status untuk installments yang telah dibayar lunas
            $this->updateCollectorTasksStatus($validated['installment_ids'], $validated['payment_amount']);

            DB::commit();

            return redirect()->route('admin.loans.show', ['id' => $validated['loan_id']])
                ->with('success', sprintf(
                    'Pembayaran sebesar Rp %s berhasil diproses untuk %d cicilan. %d cicilan telah lunas.',
                    number_format($validated['payment_amount'], 2),
                    $paymentResult['processed_count'],
                    $paymentResult['fully_paid_count']
                ));
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error for debugging
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'loan_id' => $validated['loan_id'],
                'payment_amount' => $validated['payment_amount'],
                'installment_ids' => $validated['installment_ids'],
                'processed_by' => Auth::id() // Tambahkan user yang memproses
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Update CollectorTask status untuk installments yang telah dibayar
     */
    private function updateCollectorTasksStatus(array $installmentIds, float $paymentAmount)
{
    $currentUserId = Auth::id(); // Ambil user yang sedang login
    
    // Ambil semua CollectorTask yang terkait dengan installments yang dibayar
    $collectorTasks = CollectorTask::whereIn('installment_id', $installmentIds)
        ->whereIn('status', ['pending', 'in_progress', 'assigned', 'active'])
        ->get();

    if ($collectorTasks->isEmpty()) {
        return;
    }

    // Hitung distribusi pembayaran per installment
    $installments = Installment::whereIn('id', $installmentIds)->get();
    $paymentDistribution = $this->calculatePaymentDistribution($installments, $paymentAmount);

    // Hapus CollectorTask berdasarkan installment yang dibayar
    foreach ($collectorTasks as $task) {
        // Cek status installment terkait setelah pembayaran
        $installment = Installment::find($task->installment_id);

        if ($installment) {
            // Ambil jumlah yang dibayar untuk installment ini
            $amountPaidForThisInstallment = $paymentDistribution[$installment->id] ?? 0;

            if ($installment->status === 'paid') {
                // Jika installment sudah lunas, hapus task
                Log::info('CollectorTask will be deleted due to full payment', [
                    'task_id' => $task->id,
                    'installment_id' => $task->installment_id,
                    'collector_id' => $task->collector_id,
                    'payment_amount' => $amountPaidForThisInstallment,
                    'installment_status' => $installment->status,
                    'paid_by_user_id' => $currentUserId,
                    'payment_made_by' => 'admin/finance'
                ]);

                // Hapus CollectorTask
                $task->delete();

                Log::info('CollectorTask deleted successfully', [
                    'deleted_task_id' => $task->id,
                    'installment_id' => $task->installment_id,
                    'reason' => 'Installment fully paid by admin/finance',
                    'paid_by_user_id' => $currentUserId
                ]);
            } else {
                // Jika pembayaran partial, biarkan task tetap ada
                Log::info('CollectorTask kept active due to partial payment', [
                    'task_id' => $task->id,
                    'installment_id' => $task->installment_id,
                    'collector_id' => $task->collector_id,
                    'partial_payment' => $amountPaidForThisInstallment,
                    'installment_status' => $installment->status,
                    'paid_by_user_id' => $currentUserId,
                    'payment_made_by' => 'admin/finance'
                ]);
            }
        }
    }
}

    /**
     * Hitung distribusi pembayaran per installment
     */
    private function calculatePaymentDistribution($installments, float $totalPayment): array
    {
        $distribution = [];
        $remainingPayment = $totalPayment;

        // Urutkan installments berdasarkan nomor cicilan (prioritas pembayaran)
        $sortedInstallments = collect($installments)->sortBy('installment_number');

        foreach ($sortedInstallments as $installment) {
            if ($remainingPayment <= 0) {
                $distribution[$installment->id] = 0;
                continue;
            }

            // Hitung sisa yang harus dibayar untuk installment ini
            $remainingAmount = $installment->total_due_amount - ($installment->amount_paid ?? 0);

            if ($remainingPayment >= $remainingAmount) {
                // Bayar lunas installment ini
                $distribution[$installment->id] = $remainingAmount;
                $remainingPayment -= $remainingAmount;
            } else {
                // Bayar sebagian dari installment ini
                $distribution[$installment->id] = $remainingPayment;
                $remainingPayment = 0;
            }
        }

        return $distribution;
    }

    /**
     * Calculate total remaining amount for installments
     */
    private function calculateTotalRemaining($installments)
    {
        $totalRemaining = 0;

        foreach ($installments as $installment) {
            // Hitung denda terbaru
            $currentFine = $this->calculateFine($installment);

            // Update fine jika diperlukan
            if ($currentFine > 0 && $installment->fine_amount != $currentFine) {
                $installment->fine_amount = $currentFine;
                $installment->total_due_amount = $installment->principal_amount + $installment->interest_amount + $currentFine;
                $installment->save();
            }

            $remaining = max(0, $installment->total_due_amount - ($installment->amount_paid ?? 0));
            $totalRemaining += $remaining;
        }

        return $totalRemaining;
    }

    /**
     * Process payment with improved logic
     */
    private function processPaymentImproved($installments, $validated)
    {
        $remainingPayment = $validated['payment_amount'];
        $processedCount = 0;
        $fullyPaidCount = 0;
        $paymentDetails = [];
        $currentUserId = Auth::id(); // Ambil user yang sedang login

        foreach ($installments as $installment) {
            if ($remainingPayment <= 0) break;

            // Hitung sisa yang perlu dibayar untuk cicilan ini
            $currentRemaining = max(0, $installment->total_due_amount - ($installment->amount_paid ?? 0));

            // Skip jika sudah lunas
            if ($currentRemaining <= 0) {
                continue;
            }

            // Tentukan jumlah yang akan dibayar untuk cicilan ini
            $amountToPay = min($remainingPayment, $currentRemaining);

            // Simpan detail pembayaran sebelum update
            $oldAmountPaid = $installment->amount_paid ?? 0;
            $newAmountPaid = $oldAmountPaid + $amountToPay;

            // Update pembayaran dengan user yang sedang login
            $installment->updatePayment(
                $newAmountPaid,
                $validated['payment_method'] ?? 'cash',
                $currentUserId // Pastikan menggunakan user yang sedang login
            );

            // Catat detail pembayaran
            $paymentDetails[] = [
                'installment_id' => $installment->id,
                'installment_number' => $installment->installment_number,
                'amount_paid' => $amountToPay,
                'old_amount_paid' => $oldAmountPaid,
                'new_amount_paid' => $newAmountPaid,
                'remaining_after_payment' => $installment->remaining_amount,
                'is_fully_paid' => $installment->status === 'paid',
                'paid_by_user_id' => $currentUserId // Tambahkan informasi user
            ];

            // Update counters
            $processedCount++;
            if ($installment->status === 'paid') {
                $fullyPaidCount++;
            }

            $remainingPayment -= $amountToPay;

            // Log individual payment
            $this->logPayment($installment, $amountToPay, $validated);
        }

        // Handle kelebihan pembayaran jika ada
        if ($remainingPayment > 0) {
            // Bisa disimpan sebagai overpayment atau dikembalikan
            // Untuk sekarang, kita log sebagai warning
            Log::warning('Overpayment detected', [
                'loan_id' => $validated['loan_id'],
                'overpayment_amount' => $remainingPayment,
                'total_payment' => $validated['payment_amount'],
                'processed_by' => $currentUserId
            ]);
        }

        return [
            'processed_count' => $processedCount,
            'fully_paid_count' => $fullyPaidCount,
            'payment_details' => $paymentDetails,
            'overpayment' => $remainingPayment,
            'processed_by' => $currentUserId
        ];
    }

    /**
     * Calculate fine amount for installment
     */
    private function calculateFine($installment)
    {
        $loan = $installment->loan;
        $currentDate = Carbon::now();
        $dueDate = Carbon::parse($installment->due_date);

        // Hitung denda hanya jika sudah lewat jatuh tempo dan belum lunas
        if ($currentDate->isAfter($dueDate) && $installment->status !== 'paid') {
            $daysLate = $dueDate->diffInDays($currentDate);

            // Hitung denda berdasarkan sisa yang belum dibayar
            $remainingAmount = $installment->remaining_amount ?? $installment->calculated_remaining_amount;
            $baseAmount = $remainingAmount > 0 ? $installment->principal_amount : 0;

            if ($baseAmount > 0) {
                $fineAmount = 0;

                switch ($loan->fine_unit) {
                    case 'daily':
                        $fineAmount = ($loan->fine_rate / 100) * $baseAmount * $daysLate;
                        break;
                    case 'monthly':
                        $monthsLate = ceil($daysLate / 30);
                        $fineAmount = ($loan->fine_rate / 100) * $baseAmount * $monthsLate;
                        break;
                    case 'once':
                    default:
                        $fineAmount = ($loan->fine_rate / 100) * $baseAmount;
                        break;
                }

                return max(0, $fineAmount);
            }
        }

        return 0;
    }

    /**
     * Update loan statistics after payment
     */
    /**
     * Update loan statistics after payment
     */
    private function updateLoanStatistics($loanId)
    {
        $loan = Loan::find($loanId);
        $installments = $loan->installments;

        // Hitung total yang sudah dibayar
        $totalPaid = $installments->sum('amount_paid');
        $totalPrincipalPaid = $installments->sum(function ($installment) {
            // Hitung proporsi principal yang sudah dibayar
            $paymentRatio = $installment->total_due_amount > 0 ?
                $installment->amount_paid / $installment->total_due_amount : 0;
            return $installment->principal_amount * min(1, $paymentRatio);
        });

        $totalInterestPaid = $installments->sum(function ($installment) {
            // Hitung proporsi interest yang sudah dibayar
            $paymentRatio = $installment->total_due_amount > 0 ?
                $installment->amount_paid / $installment->total_due_amount : 0;
            return $installment->interest_amount * min(1, $paymentRatio);
        });

        $totalFinesPaid = $installments->sum(function ($installment) {
            // Hitung proporsi fine yang sudah dibayar
            $paymentRatio = $installment->total_due_amount > 0 ?
                $installment->amount_paid / $installment->total_due_amount : 0;
            return $installment->fine_amount * min(1, $paymentRatio);
        });

        // Hitung sisa yang belum dibayar
        $remainingTotal = $installments->sum('remaining_amount');
        $remainingPrincipal = $loan->loan_amount - $totalPrincipalPaid;

        // Update loan
        $loan->update([
            'total_principal_paid' => $totalPrincipalPaid,
            'total_interest_paid' => $totalInterestPaid,
            'total_fines_paid' => $totalFinesPaid,
            'remaining_principal' => max(0, $remainingPrincipal),
            'remaining_interest' => max(0, $installments->sum('interest_amount') - $totalInterestPaid),
            'remaining_fines' => max(0, $installments->sum('fine_amount') - $totalFinesPaid),
        ]);

        // Update status loan jika semua installment sudah paid
        $allInstallmentsPaid = $installments->every(function ($installment) {
            return $installment->status === 'paid';
        });

        if ($allInstallmentsPaid && $installments->count() > 0) {
            $loan->update(['status' => 'completed']);
        }
    }

    /**
     * Log payment transaction
     */
    private function logPaymentTransaction($loanId, $paymentResult, $validated)
    {
        $loan = Loan::find($loanId);
        $currentUserId = Auth::id();

        activity()
            ->on($loan)
            ->withProperties([
                'payment_amount' => $validated['payment_amount'],
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'processed_installments' => $paymentResult['processed_count'],
                'fully_paid_installments' => $paymentResult['fully_paid_count'],
                'overpayment' => $paymentResult['overpayment'],
                'payment_details' => $paymentResult['payment_details'],
                'processed_by' => $currentUserId,
                'processed_at' => now(),
                'user_name' => Auth::user()->name ?? 'Unknown User' // Tambahkan nama user
            ])
            ->log('loan_payment_processed');
    }

    /**
     * Log individual payment
     */
    private function logPayment($installment, $amountPaid, $validated)
    {
        $currentUserId = Auth::id();

        activity()
            ->on($installment)
            ->withProperties([
                'amount_paid' => $amountPaid,
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'remaining_amount' => $installment->remaining_amount,
                'processed_by' => $currentUserId,
                'user_name' => Auth::user()->name ?? 'Unknown User' // Tambahkan nama user
            ])
            ->log('installment_payment_made');
    }

    /**
     * Get installments by loan ID for AJAX
     */
    public function getInstallmentsByLoan($loanId)
    {
        try {
            $installments = Installment::where('loan_id', $loanId)
                ->where('status', '!=', 'paid')
                ->orderBy('installment_number', 'asc')
                ->get()
                ->map(function ($installment) {
                    // Hitung denda jika ada
                    $fineAmount = $this->calculateFine($installment);
                    $installment->calculated_fine = $fineAmount;

                    // Hitung total yang perlu dibayar termasuk denda
                    $currentTotal = $installment->total_due_amount;
                    if ($fineAmount > 0) {
                        $currentTotal = $installment->principal_amount + $installment->interest_amount + $fineAmount;
                    }

                    $installment->total_due_with_fine = $currentTotal;
                    $installment->current_remaining_amount = max(0, $currentTotal - ($installment->amount_paid ?? 0));

                    // Format tanggal untuk display
                    $installment->formatted_due_date = Carbon::parse($installment->due_date)->format('d/m/Y');
                    $installment->is_overdue = Carbon::now()->isAfter($installment->due_date);

                    return $installment;
                });

            return response()->json([
                'success' => true,
                'data' => $installments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data cicilan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate payment summary for selected installments
     */
    public function calculatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'installment_ids' => 'required|array|min:1',
            'installment_ids.*' => 'exists:installments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data cicilan tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $installmentIds = $request->input('installment_ids');
            $installments = Installment::whereIn('id', $installmentIds)->get();

            $totalPrincipal = 0;
            $totalInterest = 0;
            $totalFines = 0;
            $totalDue = 0;
            $totalRemaining = 0;
            $overdueCount = 0;

            foreach ($installments as $installment) {
                $fineAmount = $this->calculateFine($installment);
                $currentTotal = $installment->principal_amount + $installment->interest_amount + $fineAmount;
                $remaining = max(0, $currentTotal - ($installment->amount_paid ?? 0));

                $totalPrincipal += $installment->principal_amount;
                $totalInterest += $installment->interest_amount;
                $totalFines += $fineAmount;
                $totalDue += $currentTotal;
                $totalRemaining += $remaining;

                if (Carbon::now()->isAfter($installment->due_date)) {
                    $overdueCount++;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_principal' => $totalPrincipal,
                    'total_interest' => $totalInterest,
                    'total_fines' => $totalFines,
                    'total_due' => $totalDue,
                    'total_remaining' => $totalRemaining,
                    'installment_count' => $installments->count(),
                    'overdue_count' => $overdueCount,
                    'formatted' => [
                        'total_principal' => number_format($totalPrincipal, 2),
                        'total_interest' => number_format($totalInterest, 2),
                        'total_fines' => number_format($totalFines, 2),
                        'total_due' => number_format($totalDue, 2),
                        'total_remaining' => number_format($totalRemaining, 2),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show payment details
     */
    public function show($id)
    {
        $installment = Installment::with([
            'loan.nasabah.user',
            'loan.installments',
            'payer'
        ])->findOrFail($id);

        // Hitung denda terbaru
        $currentFine = $this->calculateFine($installment);
        $installment->current_fine = $currentFine;
        $installment->current_total_due = $installment->principal_amount + $installment->interest_amount + $currentFine;
        $installment->current_remaining = max(0, $installment->current_total_due - ($installment->amount_paid ?? 0));

        return view('admin.payment.show', compact('installment'));
    }

    /**
     * Show payment history for customer
     */
    public function history($nasabahId)
    {
        $nasabah = Nasabah::with([
            'user',
            'loans.installments' => function ($query) {
                $query->where('amount_paid', '>', 0)
                    ->orderBy('payment_date', 'desc');
            },
            'loans.installments.payer'
        ])->findOrFail($nasabahId);

        // Hitung statistik pembayaran
        $totalPaid = 0;
        $totalPayments = 0;

        foreach ($nasabah->loans as $loan) {
            foreach ($loan->installments as $installment) {
                $totalPaid += $installment->amount_paid;
                if ($installment->amount_paid > 0) {
                    $totalPayments++;
                }
            }
        }

        return view('admin.payment.history', compact('nasabah', 'totalPaid', 'totalPayments'));
    }

    /**
     * Generate payment receipt
     */
    public function receipt($installmentId)
    {
        $installment = Installment::with([
            'loan.nasabah.user',
            'loan.nasabah',
            'payer'
        ])->findOrFail($installmentId);

        if ($installment->amount_paid <= 0) {
            return redirect()->back()->with('error', 'Cicilan belum memiliki pembayaran');
        }

        return view('admin.payment.receipt', compact('installment'));
    }

    /**
     * Bulk payment processing
     */
    public function bulkPayment(Request $request)
    {
        $validated = $request->validate([
            'payments' => 'required|array|min:1',
            'payments.*.installment_id' => 'required|exists:installments,id',
            'payments.*.amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:50',
        ]);

        $currentUserId = Auth::id(); // Ambil user yang sedang login

        DB::beginTransaction();

        try {
            $processedCount = 0;
            $totalAmount = 0;

            foreach ($validated['payments'] as $payment) {
                $installment = Installment::lockForUpdate()->find($payment['installment_id']);

                if ($installment && $installment->status !== 'paid') {
                    $newAmountPaid = ($installment->amount_paid ?? 0) + $payment['amount'];

                    // Pastikan menggunakan user yang sedang login
                    $installment->updatePayment(
                        $newAmountPaid,
                        $validated['payment_method'] ?? 'cash',
                        $currentUserId
                    );

                    $this->logPayment($installment, $payment['amount'], $validated);

                    $processedCount++;
                    $totalAmount += $payment['amount'];

                    // Update loan statistics
                    $this->updateLoanStatistics($installment->loan_id);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => sprintf(
                    'Berhasil memproses %d pembayaran dengan total Rp %s',
                    $processedCount,
                    number_format($totalAmount, 2)
                ),
                'processed_by' => $currentUserId,
                'processed_by_name' => Auth::user()->name ?? 'Unknown User'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bulk payment processing failed', [
                'error' => $e->getMessage(),
                'payments' => $validated['payments'],
                'processed_by' => $currentUserId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment dashboard data
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistik pembayaran hari ini
        $todayPayments = Installment::where('payment_date', '>=', $today)
            ->where('amount_paid', '>', 0)
            ->sum('amount_paid');

        // Statistik pembayaran bulan ini
        $monthlyPayments = Installment::where('payment_date', '>=', $thisMonth)
            ->where('amount_paid', '>', 0)
            ->sum('amount_paid');

        // Cicilan yang jatuh tempo hari ini
        $dueTodayCount = Installment::where('due_date', $today)
            ->where('status', '!=', 'paid')
            ->count();

        // Cicilan yang terlambat
        $overdueCount = Installment::where('due_date', '<', $today)
            ->where('status', '!=', 'paid')
            ->count();

        // Cicilan yang akan jatuh tempo dalam 7 hari
        $upcomingCount = Installment::whereBetween('due_date', [$today->copy()->addDay(), $today->copy()->addDays(7)])
            ->where('status', '!=', 'paid')
            ->count();

        return view('admin.payment.dashboard', compact(
            'todayPayments',
            'monthlyPayments',
            'dueTodayCount',
            'overdueCount',
            'upcomingCount'
        ));
    }
}
