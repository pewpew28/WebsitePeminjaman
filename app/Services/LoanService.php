<?php

namespace App\Services;

use App\Repositories\LoanRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class LoanService
{
    protected $loanRepository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
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
            return $this->loanRepository->create($data);
        } catch (Exception $e) {
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