<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository
{
    protected $model;

    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    /**
     * Get all settings
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find setting by ID
     *
     * @param int $id
     * @return Setting|null
     */
    public function findById(int $id): ?Setting
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find setting by key
     *
     * @param string $key
     * @return Setting|null
     */
    public function findByKey(string $key): ?Setting
    {
        return $this->model->where('key', $key)->first();
    }

    /**
     * Create a new setting
     *
     * @param array $data
     * @return Setting
     */
    public function create(array $data): Setting
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing setting
     *
     * @param int $id
     * @param array $data
     * @return Setting
     */
    public function update(int $id, array $data): Setting
    {
        $setting = $this->findById($id);
        $setting->update($data);
        return $setting;
    }

    /**
     * Update or create a setting by key
     *
     * @param string $key
     * @param string $value
     * @return Setting
     */
    public function updateOrCreateByKey(string $key, string $value): Setting
    {
        return $this->model->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Delete a setting
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $setting = $this->findById($id);
        return $setting->delete();
    }

    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getValueByKey(string $key, $default = null)
    {
        $setting = $this->findByKey($key);
        return $setting ? $setting->value : $default;
    }
}