<?php

namespace App\Repositories;

use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Get all users with optional filtering
     *
     * @param array $filters
     * @return Collection
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        return $query->with(['nasabahs', 'approvedLoans', 'receivedInstallments', 'collectorTasks'])
                    ->get();
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->model->with(['nasabahs', 'approvedLoans', 'receivedInstallments', 'collectorTasks'])
                          ->findOrFail($id);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }

    /**
     * Update an existing user
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user;
    }

    /**
     * Soft delete a user
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        return $user->delete();
    }

    /**
     * Restore a soft-deleted user
     *
     * @param int $id
     * @return User
     */
    public function restore(int $id): User
    {
        $user = $this->model->withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }

    /**
     * Upload or update user profile picture
     *
     * @param int $id
     * @param mixed $file
     * @return Media
     */
    public function uploadProfilePicture(int $id, $file): Media
    {
        $user = $this->findById($id);
        return $user->addMedia($file)
                    ->toMediaCollection('profile_pictures');
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Update user's last seen timestamp
     *
     * @param int $id
     * @return User
     */
    public function updateLastSeen(int $id): User
    {
        $user = $this->findById($id);
        $user->last_seen_at = now();
        $user->save();
        return $user;
    }
}