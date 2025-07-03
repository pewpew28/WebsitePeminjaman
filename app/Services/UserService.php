<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(array $filters = [])
    {
        try {
            return $this->userRepository->getAll($filters);
        } catch (Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            throw new Exception('Failed to fetch users');
        }
    }

    public function getUserById(int $id)
    {
        try {
            return $this->userRepository->findById($id);
        } catch (Exception $e) {
            Log::error('Error fetching user by ID: ' . $e->getMessage());
            throw new Exception('Failed to fetch user');
        }
    }

    public function createUser(array $data)
    {
        try {
            return $this->userRepository->create($data);
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw new Exception('Failed to create user');
        }
    }

    public function updateUser(int $id, array $data)
    {
        try {
            return $this->userRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            throw new Exception('Failed to update user');
        }
    }

    public function deleteUser(int $id)
    {
        try {
            return $this->userRepository->delete($id);
        } catch (Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw new Exception('Failed to delete user');
        }
    }

    public function restoreUser(int $id)
    {
        try {
            return $this->userRepository->restore($id);
        } catch (Exception $e) {
            Log::error('Error restoring user: ' . $e->getMessage());
            throw new Exception('Failed to restore user');
        }
    }

    public function uploadProfilePicture(int $id, $file)
    {
        try {
            return $this->userRepository->uploadProfilePicture($id, $file);
        } catch (Exception $e) {
            Log::error('Error uploading profile picture: ' . $e->getMessage());
            throw new Exception('Failed to upload profile picture');
        }
    }

    public function updateLastSeen(int $id)
    {
        try {
            return $this->userRepository->updateLastSeen($id);
        } catch (Exception $e) {
            Log::error('Error updating last seen: ' . $e->getMessage());
            throw new Exception('Failed to update last seen');
        }
    }
}