<?php

namespace App\Repositories;

use App\Models\Nasabah;
use Illuminate\Database\Eloquent\Collection;

class NasabahRepository
{
    protected $model;

    public function __construct(Nasabah $nasabah)
    {
        $this->model = $nasabah;
    }

    /**
     * Get all nasabah with optional filtering
     *
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with(['user', 'loans', 'collectorTasks'])
                    ->get();
    }

    /**
     * Find nasabah by ID
     *
     * @param int $id
     * @return Nasabah|null
     */
    public function findById(int $id): ?Nasabah
    {
        return $this->model->with(['user', 'loans.installments', 'collectorTasks'])
                          ->findOrFail($id);
    }

    /**
     * Create a new nasabah
     *
     * @param array $data
     * @return Nasabah
     */
    public function create(array $data): Nasabah
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing nasabah
     *
     * @param int $id
     * @param array $data
     * @return Nasabah
     */
    public function update(int $id, array $data): Nasabah
    {
        $nasabah = $this->findById($id);
        $nasabah->update($data);
        return $nasabah;
    }

    /**
     * Soft delete a nasabah
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $nasabah = $this->findById($id);
        return $nasabah->delete();
    }

    /**
     * Restore a soft-deleted nasabah
     *
     * @param int $id
     * @return Nasabah
     */
    public function restore(int $id): Nasabah
    {
        $nasabah = $this->model->withTrashed()->findOrFail($id);
        $nasabah->restore();
        return $nasabah;
    }

    /**
     * Find nasabah by email
     *
     * @param string $email
     * @return Nasabah|null
     */
    public function findByEmail(string $email): ?Nasabah
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find nasabah by ID card number
     *
     * @param string $idCardNumber
     * @return Nasabah|null
     */
    public function findByIdCardNumber(string $idCardNumber): ?Nasabah
    {
        return $this->model->where('id_card_number', $idCardNumber)->first();
    }
}