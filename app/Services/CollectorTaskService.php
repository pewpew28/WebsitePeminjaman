<?php

namespace App\Services;

use App\Repositories\CollectorTaskRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class CollectorTaskService
{
    protected $collectorTaskRepository;

    public function __construct(CollectorTaskRepository $collectorTaskRepository)
    {
        $this->collectorTaskRepository = $collectorTaskRepository;
    }

    public function getAllCollectorTasks(array $filters = [])
    {
        try {
            return $this->collectorTaskRepository->getAll($filters);
        } catch (Exception $e) {
            Log::error('Error fetching collector tasks: ' . $e->getMessage());
            throw new Exception('Failed to fetch collector tasks');
        }
    }

    public function getCollectorTaskById(int $id)
    {
        try {
            return $this->collectorTaskRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error fetching collector task by ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch collector task');
        }
    }

    public function createCollectorTask(array $data)
    {
        try {
            return $this->collectorTaskRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating collector task: ' . $e->getMessage());
            throw new Exception('Failed to create collector task');
        }
    }

    public function updateCollectorTask(int $id, array $data)
    {
        try {
            return $this->collectorTaskRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating collector task: ' . $e->getMessage());
            throw new Exception('Failed to update collector task');
        }
    }

    public function deleteCollectorTask(int $id)
    {
        try {
            return $this->collectorTaskRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting collector task: ' . $e->getMessage());
            throw new Exception('Failed to delete collector task');
        }
    }

    public function restoreCollectorTask(int $id)
    {
        try {
            return $this->collectorTaskRepository->restore($id);
        } catch (Exception $e) {
            Log::error('Error restoring collector task: ' . $e->getMessage());
            throw new Exception('Failed to restore collector task');
        }
    }

    public function recordCollection(int $id, float $amountCollected, array $data)
    {
        try {
            return $this->collectorTaskRepository->recordCollection($id, $amountCollected, $data);
        } catch (Exception $e) {
            Log::error('Error recording collection: ' . $e->getMessage());
            throw new Exception('Failed to record collection');
        }
    }
}