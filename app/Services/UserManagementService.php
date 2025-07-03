<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementService
{
    public function getFilteredUsers(array $filters): LengthAwarePaginator
    {
        $query = User::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'active') {
                $query->whereNotNull('last_login_at');
            } else {
                $query->whereNull('last_login_at');
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getUserStats(): array
    {
        return [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'finance' => User::where('role', 'finance')->count(),
            'collector' => User::where('role', 'collector')->count(),
            'nasabah' => User::where('role', 'nasabah')->count(),
        ];
    }

    public function createUser(array $data, ?UploadedFile $profilePicture = null): User
    {
        return DB::transaction(function () use ($data, $profilePicture) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'role' => $data['role'],
                'password' => Hash::make($data['password']),
                'address' => $data['address'] ?? null,
                'email_verified_at' => now(),
            ]);

            if ($profilePicture) {
                $user->addMediaFromRequest('profile_picture')
                    ->toMediaCollection('profile_pictures');
            }

            return $user;
        });
    }

    public function updateUser(User $user, array $data, ?UploadedFile $profilePicture = null): User
    {
        return DB::transaction(function () use ($user, $data, $profilePicture) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'role' => $data['role'],
                'address' => $data['address'] ?? null,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            if ($profilePicture) {
                $user->clearMediaCollection('profile_pictures');
                $user->addMediaFromRequest('profile_picture')
                    ->toMediaCollection('profile_pictures');
            }

            return $user;
        });
    }

    public function deleteUser(User $user): void
    {
        $this->validateUserDeletion($user);

        DB::transaction(function () use ($user) {
            $user->clearMediaCollection('profile_pictures');
            $user->delete();
        });
    }

    public function restoreUser(int $userId): User
    {
        $user = User::withTrashed()->findOrFail($userId);

        $emailExists = User::where('email', $user->email)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            throw new \InvalidArgumentException(
                'Email sudah digunakan oleh user lain. Tidak dapat memulihkan user.'
            );
        }

        $user->restore();
        return $user;
    }

    public function forceDeleteUser(int $userId): void
    {
        $user = User::withTrashed()->findOrFail($userId);

        DB::transaction(function () use ($user) {
            $user->clearMediaCollection('profile_pictures');
            $user->forceDelete();
        });
    }

    public function getTrashedUsers(array $filters): LengthAwarePaginator
    {
        $query = User::onlyTrashed();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        return $query->orderBy('deleted_at', 'desc')->paginate(10);
    }

    public function toggleUserStatus(User $user): User
    {
        $user->update([
            'last_seen_at' => $user->last_seen_at ? null : now()
        ]);

        return $user;
    }

    private function validateUserDeletion(User $user): void
    {
        // Check if this is the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                throw new \InvalidArgumentException(
                    'Tidak dapat menghapus admin terakhir.'
                );
            }
        }

        // Check if user is currently logged in
        if (auth()->id() === $user->id) {
            throw new \InvalidArgumentException(
                'Tidak dapat menghapus akun Anda sendiri.'
            );
        }

        // Check for active relations
        $this->checkActiveRelations($user);
    }

    private function checkActiveRelations(User $user): void
    {
        $relationMessages = [];

        // Check registered customers
        $nasabahCount = $user->nasabahs()->count();
        if ($nasabahCount > 0) {
            $relationMessages[] = "memiliki {$nasabahCount} nasabah terdaftar";
        }

        // Check approved loans
        $approvedLoansCount = $user->approvedLoans()->count();
        if ($approvedLoansCount > 0) {
            $relationMessages[] = "telah menyetujui {$approvedLoansCount} pinjaman";
        }

        // Check received installments
        $receivedInstallmentsCount = $user->receivedInstallments()->count();
        if ($receivedInstallmentsCount > 0) {
            $relationMessages[] = "telah menerima {$receivedInstallmentsCount} pembayaran cicilan";
        }

        // Check collector tasks
        $collectorTasksCount = $user->collectorTasks()->count();
        if ($collectorTasksCount > 0) {
            $relationMessages[] = "memiliki {$collectorTasksCount} tugas collector";
        }

        if (!empty($relationMessages)) {
            $message = "User tidak dapat dihapus karena " . implode(', ', $relationMessages) . ".";
            throw new \InvalidArgumentException($message);
        }
    }
}