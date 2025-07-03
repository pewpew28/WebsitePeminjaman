<?php

namespace App\Repositories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection;

class LoanRepository
{
    protected $model;

    public function __construct(Loan $loan)
    {
        $this->model = $loan;
    }

    /**
     * Get all loans with optional filtering
     *
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters['nasabah_id'])) {
            $query->where('nasabah_id', $filters['nasabah_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['approved_by'])) {
            $query->where('approved_by', $filters['approved_by']);
        }

        return $query->with(['nasabah', 'approver', 'installments', 'originalLoan', 'refinancedLoans', 'collectorTasks'])
                    ->get();
    }

    /**
     * Find loan by ID
     *
     * @param int $id
     * @return Loan|null
     */
    public function findById(int $id): ?Loan
    {
        return $this->model->with(['nasabah', 'approver', 'installments', 'originalLoan', 'refinancedLoans', 'collectorTasks'])
                          ->findOrFail($id);
    }

    /**
     * Create a new loan
     *
     * @param array $data
     * @return Loan
     */
    public function create(array $data): Loan
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing loan
     *
     * @param int $id
     * @param array $data
     * @return Loan
     */
    public function update(int $id, array $data): Loan
    {
        $loan = $this->findById($id);
        $loan->update($data);
        return $loan;
    }

    /**
     * Soft delete a loan
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $loan = $this->findById($id);
        return $loan->delete();
    }

    /**
     * Restore a soft-deleted loan
     *
     * @param int $id
     * @return Loan
     */
    public function restore(int $id): Loan
    {
        $loan = $this->model->withTrashed()->findOrFail($id);
        $loan->restore();
        return $loan;
    }

    /**
     * Approve a loan
     *
     * @param int $id
     * @param int $approverId
     * @return Loan
     */
    public function approve(int $id, int $approverId): Loan
    {
        $loan = $this->findById($id);
        $loan->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'disbursement_date' => now(),
        ]);
        return $loan;
    }

    /**
     * Get loans by nasabah ID
     *
     * @param int $nasabahId
     * @return Collection
     */
    public function findByNasabahId(int $nasabahId): Collection
    {
        return $this->model->where('nasabah_id', $nasabahId)
                          ->with(['nasabah', 'approver', 'installments'])
                          ->get();
    }

    /**
     * Get refinanced loans for a specific loan
     *
     * @param int $originalLoanId
     * @return Collection
     */
    public function findRefinancedLoans(int $originalLoanId): Collection
    {
        return $this->model->where('original_loan_id', $originalLoanId)
                          ->with(['nasabah', 'approver'])
                          ->get();
    }
}