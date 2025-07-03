<?php

namespace App\Services;

use App\Repositories\InstallmentRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class InstallmentService
{
    protected $installmentRepository;

    public function __construct(InstallmentRepository $installmentRepository)
    {
        $this->installmentRepository = $installmentRepository;
    }

    public function getAllInstallments(array $filters = [])
    {
        try {
            return $this->installmentRepository->getAll($filters);
        } catch (Exception $e) {
            Log::error('Error fetching installments: ' . $e->getMessage());
            throw new Exception('Failed to fetch installments');
        }
    }

    public function getInstallmentById(int $id)
    {
        try {
            return $this->installmentRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error fetching installment by ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch installment');
        }
    }

    public function createInstallment(array $data)
    {
        try {
            return $this->installmentRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating installment: ' . $e->getMessage());
            throw new Exception('Failed to create installment');
        }
    }

    public function updateInstallment(int $id, array $data)
    {
        try {
            return $this->installmentRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating installment: ' . $e->getMessage());
            throw new Exception('Failed to update installment');
        }
    }

    public function deleteInstallment(int $id)
    {
        try {
            return $this->installmentRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting installment: ' . $e->getMessage());
            throw new Exception('Failed to delete installment');
        }
    }

    public function restoreInstallment(int $id)
    {
        try {
            return $this->installmentRepository->restore($id);
        } catch (Exception $e) {
            Log::error('Error restoring installment: ' . $e->getMessage());
            throw new Exception('Failed to restore installment');
        }
    }

    public function recordPayment(int $id, array $data, int $payerId)
    {
        try {
            return $this->installmentRepository->recordPayment($id, $data, $payerId);
        } catch (Exception $e) {
            Log::error('Error recording payment: ' . $e->getMessage());
            throw new Exception('Failed to record payment');
        }
    }

    public function uploadPaymentProof(int $id, $file)
    {
        try {
            return $this->installmentRepository->uploadPaymentProof($id, $file);
        } catch (Exception $e) {
            Log::error('Error uploading payment proof: ' . $e->getMessage());
            throw new Exception('Failed to upload payment proof');
        }
    }

    /**
     * Create installments for a loan
     */
    public function createInstallmentsForLoan($loan, array $data)
    {
        try {
            $tenor = $data['loan_term'] ?? 30; // Default 30 hari jika tidak ada
            $loanAmount = $loan->loan_amount ?? $data['loan_amount'];
            $interestRate = $data['interest_rate'] ?? 0; // Dalam persen
            
            // Hitung total bunga
            $totalInterest = ($loanAmount * $interestRate) / 100;
            $totalAmount = $loanAmount;
            
            // Hitung jumlah per installment
            $principalPerInstallment = $loanAmount / $tenor;
            $interestPerInstallment = $totalInterest / $tenor;
            $totalPerInstallment = $totalAmount / $tenor;
            
            $installments = [];
            $startDate = Carbon::parse($loan->start_date ?? $data['start_date'] ?? now());
            
            for ($i = 1; $i <= $tenor; $i++) {
                $dueDate = $startDate->copy()->addDays($i);
                
                $installments[] = [
                    'loan_id' => $loan->id,
                    'installment_number' => $i,
                    'due_date' => $dueDate,
                    'principal_amount' => round($principalPerInstallment, 2),
                    'interest_amount' => round($interestPerInstallment, 2),
                    'fine_amount' => 0,
                    'total_due_amount' => round($totalPerInstallment, 2),
                    'amount_paid' => 0,
                    'payment_date' => null,
                    'status' => 'pending', // pending, paid, overdue
                    'paid_by_user_id' => null,
                    'payment_method' => null,
                    'notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Batch insert installments untuk performa yang lebih baik
            DB::table('installments')->insert($installments);
            
            Log::info("Created {$tenor} installments for loan ID: {$loan->id}");
            
            return $installments;
            
        } catch (Exception $e) {
            Log::error('Error creating installments for loan: ' . $e->getMessage());
            throw new Exception('Failed to create installments for loan');
        }
    }

    /**
     * Create weekly installments for a loan
     */
    public function createWeeklyInstallmentsForLoan($loan, array $data)
    {
        try {
            $tenor = $data['loan_term'] ?? 10; // 10 minggu
            $loanAmount = $loan->loan_amount ?? $data['loan_amount'];
            $interestRate = $data['interest_rate'] ?? 0;
            
            $totalInterest = ($loanAmount * $interestRate) / 100;
            $totalAmount = $loanAmount;
            
            $principalPerInstallment = $loanAmount / $tenor;
            $interestPerInstallment = $totalInterest / $tenor;
            $totalPerInstallment = $totalAmount / $tenor;
            
            $installments = [];
            $startDate = Carbon::parse($loan->start_date ?? $data['start_date'] ?? now());
            
            for ($i = 1; $i <= $tenor; $i++) {
                $dueDate = $startDate->copy()->addWeeks($i);
                
                $installments[] = [
                    'loan_id' => $loan->id,
                    'installment_number' => $i,
                    'due_date' => $dueDate,
                    'principal_amount' => round($principalPerInstallment, 2),
                    'interest_amount' => round($interestPerInstallment, 2),
                    'fine_amount' => 0,
                    'total_due_amount' => round($totalPerInstallment, 2),
                    'amount_paid' => 0,
                    'payment_date' => null,
                    'status' => 'pending',
                    'paid_by_user_id' => null,
                    'payment_method' => null,
                    'notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('installments')->insert($installments);
            
            Log::info("Created {$tenor} weekly installments for loan ID: {$loan->id}");
            
            return $installments;
            
        } catch (Exception $e) {
            Log::error('Error creating weekly installments for loan: ' . $e->getMessage());
            throw new Exception('Failed to create weekly installments for loan');
        }
    }

    /**
     * Create monthly installments for a loan
     */
    public function createMonthlyInstallmentsForLoan($loan, array $data)
    {
        try {
            $tenor = $data['loan_term'] ?? 12; // 12 bulan
            $loanAmount = $loan->loan_amount ?? $data['loan_amount'];
            $interestRate = $data['interest_rate'] ?? 0;
            
            $totalInterest = ($loanAmount * $interestRate) / 100;
            $totalAmount = $loanAmount;
            
            $principalPerInstallment = $loanAmount / $tenor;
            $interestPerInstallment = $totalInterest / $tenor;
            $totalPerInstallment = $totalAmount / $tenor;
            
            $installments = [];
            $startDate = Carbon::parse($loan->start_date ?? $data['start_date'] ?? now());
            
            for ($i = 1; $i <= $tenor; $i++) {
                $dueDate = $startDate->copy()->addMonths($i);
                
                $installments[] = [
                    'loan_id' => $loan->id,
                    'installment_number' => $i,
                    'due_date' => $dueDate,
                    'principal_amount' => round($principalPerInstallment, 2),
                    'interest_amount' => round($interestPerInstallment, 2),
                    'fine_amount' => 0,
                    'total_due_amount' => round($totalPerInstallment, 2),
                    'amount_paid' => 0,
                    'payment_date' => null,
                    'status' => 'pending',
                    'paid_by_user_id' => null,
                    'payment_method' => null,
                    'notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('installments')->insert($installments);
            
            Log::info("Created {$tenor} monthly installments for loan ID: {$loan->id}");
            
            return $installments;
            
        } catch (Exception $e) {
            Log::error('Error creating monthly installments for loan: ' . $e->getMessage());
            throw new Exception('Failed to create monthly installments for loan');
        }
    }

    /**
     * Get installments by loan ID
     */
    public function getInstallmentsByLoanId(int $loanId)
    {
        try {
            return $this->installmentRepository->getAll(['loan_id' => $loanId]);
        } catch (Exception $e) {
            Log::error('Error fetching installments by loan ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch installments by loan ID');
        }
    }

    /**
     * Get overdue installments
     */
    public function getOverdueInstallments()
    {
        try {
            return $this->installmentRepository->getAll([
                'status' => 'pending',
                'due_date_before' => now()->format('Y-m-d')
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching overdue installments: ' . $e->getMessage());
            throw new Exception('Failed to fetch overdue installments');
        }
    }
}