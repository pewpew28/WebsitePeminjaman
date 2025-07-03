<?php

namespace App\Repositories;

use App\Models\CollectorTask;
use Illuminate\Database\Eloquent\Collection;

class CollectorTaskRepository
{
    protected $model;

    public function __construct(CollectorTask $collectorTask)
    {
        $this->model = $collectorTask;
    }

    /**
     * Get all collector tasks with optional filtering
     *
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters['collector_id'])) {
            $query->where('collector_id', $filters['collector_id']);
        }

        if (!empty($filters['nasabah_id'])) {
            $query->where('nasabah_id', $filters['nasabah_id']);
        }

        if (!empty($filters['loan_id'])) {
            $query->where('loan_id', $filters['loan_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with(['collector', 'nasabah', 'loan'])
                    ->get();
    }

    /**
     * Find collector task by ID
     *
     * @param int $id
     * @return CollectorTask|null
     */
    public function findById(int $id): ?CollectorTask
    {
        return $this->model->with(['collector', 'nasabah', 'loan'])
                          ->findOrFail($id);
    }

    /**
     * Create a new collector task
     *
     * @param array $data
     * @return CollectorTask
     */
    public function create(array $data): CollectorTask
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing collector task
     *
     * @param int $id
     * @param array $data
     * @return CollectorTask
     */
    public function update(int $id, array $data): CollectorTask
    {
        $collectorTask = $this->findById($id);
        $collectorTask->update($data);
        return $collectorTask;
    }

    /**
     * Soft delete a collector task
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $collectorTask = $this->findById($id);
        return $collectorTask->delete();
    }

    /**
     * Restore a soft-deleted collector task
     *
     * @param int $id
     * @return CollectorTask
     */
    public function restore(int $id): CollectorTask
    {
        $collectorTask = $this->model->withTrashed()->findOrFail($id);
        $collectorTask->restore();
        return $collectorTask;
    }

    /**
     * Record collection for a task
     *
     * @param int $id
     * @param float $amountCollected
     * @param array $data
     * @return CollectorTask
     */
    public function recordCollection(int $id, float $amountCollected, array $data): CollectorTask
    {
        $collectorTask = $this->findById($id);
        $collectorTask->update([
            'amount_collected_during_task' => $amountCollected,
            'actual_visit_date' => now(),
            'status' => $data['status'] ?? 'completed',
            'notes' => $data['notes'] ?? null,
        ]);
        return $collectorTask;
    }

    /**
     * Get collector tasks by collector ID
     *
     * @param int $collectorId
     * @return Collection
     */
    public function findByCollectorId(int $collectorId): Collection
    {
        return $this->model->where('collector_id', $collectorId)
                          ->with(['collector', 'nasabah', 'loan'])
                          ->get();
    }

    /**
     * Get collector tasks by nasabah ID
     *
     * @param int $nasabahId
     * @return Collection
     */
    public function findByNasabahId(int $nasabahId): Collection
    {
        return $this->model->where('nasabah_id', $nasabahId)
                          ->with(['collector', 'nasabah', 'loan'])
                          ->get();
    }

    /**
     * Get collector tasks by loan ID
     *
     * @param int $loanId
     * @return Collection
     */
    public function findByLoanId(int $loanId): Collection
    {
        return $this->model->where('loan_id', $loanId)
                          ->with(['collector', 'nasabah', 'loan'])
                          ->get();
    }
}