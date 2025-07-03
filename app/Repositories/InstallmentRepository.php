<?php

namespace App\Repositories;

use App\Models\Installment;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Collection;

class InstallmentRepository
{
    protected $model;

    public function __construct(Installment $installment)
    {
        $this->model = $installment;
    }

    /**
     * Get all installments with optional filtering
     *
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters['loan_id'])) {
            $query->where('loan_id', $filters['loan_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['paid_by_user_id'])) {
            $query->where('paid_by_user_id', $filters['paid_by_user_id']);
        }

        return $query->with(['loan', 'payer'])
                    ->get();
    }

    /**
     * Find installment by ID
     *
     * @param int $id
     * @return Installment|null
     */
    public function findById(int $id): ?Installment
    {
        return $this->model->with(['loan', 'payer'])
                          ->findOrFail($id);
    }

    /**
     * Create a new installment
     *
     * @param array $data
     * @return Installment
     */
    public function create(array $data): Installment
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing installment
     *
     * @param int $id
     * @param array $data
     * @return Installment
     */
    public function update(int $id, array $data): Installment
    {
        $installment = $this->findById($id);
        $installment->update($data);
        return $installment;
    }

    /**
     * Soft delete an installment
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $installment = $this->findById($id);
        return $installment->delete();
    }

    /**
     * Restore a soft-deleted installment
     *
     * @param int $id
     * @return Installment
     */
    public function restore(int $id): Installment
    {
        $installment = $this->model->withTrashed()->findOrFail($id);
        $installment->restore();
        return $installment;
    }

    /**
     * Record a payment for an installment
     *
     * @param int $id
     * @param array $data
     * @param int $payerId
     * @return Installment
     */
    public function recordPayment(int $id, array $data, int $payerId): Installment
    {
        $installment = $this->findById($id);
        $installment->update([
            'amount_paid' => $data['amount_paid'],
            'payment_date' => now(),
            'status' => $data['status'] ?? 'paid',
            'paid_by_user_id' => $payerId,
            'payment_method' => $data['payment_method'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
        return $installment;
    }

    /**
     * Upload payment proof for an installment
     *
     * @param int $id
     * @param mixed $file
     * @return Media
     */
    public function uploadPaymentProof(int $id, $file): Media
    {
        $installment = $this->findById($id);
        return $installment->addMedia($file)
                          ->toMediaCollection('payment_proofs');
    }

    /**
     * Get installments by loan ID
     *
     * @param int $loanId
     * @return Collection
     */
    public function findByLoanId(int $loanId): Collection
    {
        return $this->model->where('loan_id', $loanId)
                          ->with(['loan', 'payer'])
                          ->get();
    }
}