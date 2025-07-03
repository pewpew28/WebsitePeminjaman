<?php

namespace App\Services;

use App\Repositories\NasabahRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class NasabahService
{
    protected $nasabahRepository;

    public function __construct(NasabahRepository $nasabahRepository)
    {
        $this->nasabahRepository = $nasabahRepository;
    }

    public function getAllNasabahs(array $filters = [])
    {
        try {
            return $this->nasabahRepository->getAll($filters);
        } catch (Exception $e) {
            Log::error('Error fetching nasabahs: ' . $e->getMessage());
            throw new Exception('Failed to fetch nasabahs');
        }
    }

    public function getNasabahById(int $id)
    {
        try {
            return $this->nasabahRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error fetching nasabah by ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch nasabah');
        }
    }

    public function createNasabah(array $data)
    {
        try {
            return $this->nasabahRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating nasabah: ' . $e->getMessage());
            throw new Exception('Failed to create nasabah');
        }
    }

    public function updateNasabah(int $id, array $data)
    {
        try {
            return $this->nasabahRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating nasabah: ' . $e->getMessage());
            throw new Exception('Failed to update nasabah');
        }
    }

    public function deleteNasabah(int $id)
    {
        try {
            return $this->nasabahRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting nasabah: ' . $e->getMessage());
            throw new Exception('Failed to delete nasabah');
        }
    }

    public function restoreNasabah(int $id)
    {
        try {
            return $this->nasabahRepository->restore($id);
        } catch (Exception $e) {
            Log::error('Error restoring nasabah: ' . $e->getMessage());
            throw new Exception('Failed to restore nasabah');
        }
    }
}