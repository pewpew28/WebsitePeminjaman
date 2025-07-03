<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class SettingService
{
    protected $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAllSettings()
    {
        try {
            return $this->settingRepository->getAll();
        } catch (Exception $e) {
            Log::error('Error fetching settings: ' . $e->getMessage());
            throw new Exception('Failed to fetch settings');
        }
    }

    public function getSettingById(int $id)
    {
        try {
            return $this->settingRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error fetching setting by ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch setting');
        }
    }

    public function getSettingByKey(string $key)
    {
        try {
            return $this->settingRepository->findByKey($key);
        } catch (Exception $e) {
            Log::error('Error fetching setting by key: ' . $e->getMessage());
            throw new Exception('Failed to fetch setting');
        }
    }

    public function createSetting(array $data)
    {
        try {
            return $this->settingRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating setting: ' . $e->getMessage());
            throw new Exception('Failed to create setting');
        }
    }

    public function updateSetting(int $id, array $data)
    {
        try {
            return $this->settingRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating setting: ' . $e->getMessage());
            throw new Exception('Failed to update setting');
        }
    }

    public function updateOrCreateSettingByKey(string $key, string $value)
    {
        try {
            return $this->settingRepository->updateOrCreateByKey($key, $value);
        } catch (Exception $e) {
            Log::error('Error updating or creating setting: ' . $e->getMessage());
            throw new Exception('Failed to update or create setting');
        }
    }

    public function deleteSetting(int $id)
    {
        try {
            return $this->settingRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting setting: ' . $e->getMessage());
            throw new Exception('Failed to delete setting');
        }
    }

    public function getValueByKey(string $key, $default = null)
    {
        try {
            return $this->settingRepository->getValueByKey($key, $default);
        } catch (Exception $e) {
            Log::error('Error fetching setting value by key: ' . $e->getMessage());
            throw new Exception('Failed to fetch setting value');
        }
    }
}