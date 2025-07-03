<?php

namespace App\Services;

use App\Repositories\LoanRepository;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class LoanService
{
    protected $loanRepository;
    protected $installmentService;

    public function __construct(
        LoanRepository $loanRepository,
        InstallmentService $installmentService
    ) {
        $this->loanRepository = $loanRepository;
        $this->installmentService = $installmentService;
    }

    public function getAllLoans(array $filters = [])
    {
        try {
            return $this->loanRepository->getAll($filters);
        } catch (Exception $e) {
            Log::error('Error fetching loans: ' . $e->getMessage());
            throw new Exception('Failed to fetch loans');
        }
    }

    public function getLoanById(int $id)
    {
        try {
            return $this->loanRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error fetching loan by ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch loan');
        }
    }

    public function createLoan(array $data)
    {
        try {
            DB::beginTransaction();
            
            // Create loan
            $loan = $this->loanRepository->create($data);
            
            // Create installments based on installment type
            $installmentType = $data['term_unit'] ?? 'daily'; // daily, weekly, monthly
            
            switch ($installmentType) {
                case 'weekly':
                    $this->installmentService->createWeeklyInstallmentsForLoan($loan, $data);
                    break;
                case 'monthly':
                    $this->installmentService->createMonthlyInstallmentsForLoan($loan, $data);
                    break;
                case 'daily':
                default:
                    $this->installmentService->createInstallmentsForLoan($loan, $data);
                    break;
            }
            
            DB::commit();
            
            Log::info("Loan created successfully with ID: {$loan->id}");
            
            return $loan;
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating loan: ' . $e->getMessage());
            throw new Exception('Failed to create loan');
        }
    }

    public function updateLoan(int $id, array $data)
    {
        try {
            return $this->loanRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating loan: ' . $e->getMessage());
            throw new Exception('Failed to update loan');
        }
    }

    public function deleteLoan(int $id)
    {
        try {
            return $this->loanRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting loan: ' . $e->getMessage());
            throw new Exception('Failed to delete loan');
        }
    }

    public function restoreLoan(int $id)
    {
        try {
            return $this->loanRepository->restore($id);
        } catch (Exception $e) {
            Log::error('Error restoring loan: ' . $e->getMessage());
            throw new Exception('Failed to restore loan');
        }
    }

    public function approveLoan(int $id, int $approverId)
    {
        try {
            return $this->loanRepository->approve($id, $approverId);
        } catch (Exception $e) {
            Log::error('Error approving loan: ' . $e->getMessage());
            throw new Exception('Failed to approve loan');
        }
    }
}