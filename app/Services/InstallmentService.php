<?php

namespace App\Services;

use App\Repositories\InstallmentRepository;
use Illuminate\Support\Facades\Log;
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
}