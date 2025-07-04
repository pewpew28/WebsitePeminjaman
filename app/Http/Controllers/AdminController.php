<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\Nasabah;
use App\Models\User;
use App\Services\AdminDashboardService;
use App\Services\UserManagementService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    private AdminDashboardService $dashboardService;
    private UserManagementService $userService;

    public function __construct(
        AdminDashboardService $dashboardService,
        UserManagementService $userService
    ) {
        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
    }

    public function dashboard()
    {
        $cacheKey = 'admin_dashboard_' . Carbon::now()->format('Y-m-d-H');

        $dashboardData = Cache::remember($cacheKey, 3600, function () {
            return [
                'financialSummary' => $this->dashboardService->getFinancialSummary(),
                'customerStats' => $this->dashboardService->getCustomerStats(),
                'recentActivities' => $this->dashboardService->getRecentActivities(),
            ];
        });
        // dd($dashboardData['financialSummary']);

        return view('admin.dashboard', $dashboardData);
    }

    public function userIndex(Request $request)
    {
        $filters = $request->only(['search', 'role', 'status']);
        $users = $this->userService->getFilteredUsers($filters);
        $stats = $this->userService->getUserStats();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $validatedData = $request->validate($this->getUserValidationRules());

        try {
            $user = $this->userService->createUser($validatedData, $request->file('profile_picture'));
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }

    public function userShow(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function userEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, User $user)
    {
        $validatedData = $request->validate($this->getUserValidationRules($user->id));

        try {
            $this->userService->updateUser($user, $validatedData, $request->file('profile_picture'));
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function userDestroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('admin.users.index')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage());
        }
    }

    public function userRestore($id)
    {
        try {
            $this->userService->restoreUser($id);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dipulihkan.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memulihkan user: ' . $e->getMessage());
        }
    }

    public function userForceDelete($id)
    {
        try {
            $this->userService->forceDeleteUser($id);
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus permanen user: ' . $e->getMessage());
        }
    }

    public function userTrashed(Request $request)
    {
        $filters = $request->only(['search', 'role']);
        $trashedUsers = $this->userService->getTrashedUsers($filters);

        return view('admin.users.trashed', compact('trashedUsers'));
    }

    public function userToggleStatus(User $user)
    {
        try {
            $this->userService->toggleUserStatus($user);
            $status = $user->fresh()->last_seen_at ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                ->with('success', "User berhasil {$status}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status user: ' . $e->getMessage());
        }
    }

    public function nasabahIndex(Request $request)
    {
        $query = Nasabah::with(['user', 'loans'])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $request->search . '%')
                        ->orWhere('id_card_number', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->gender, function ($q) use ($request) {
                $q->where('gender', $request->gender);
            })
            ->when($request->occupation, function ($q) use ($request) {
                $q->where('occupation', 'like', '%' . $request->occupation . '%');
            });

        // Handle with trashed if requested
        if ($request->with_trashed) {
            $query->withTrashed();
        }

        $nasabah = $query->latest()->paginate(10);

        // Get statistics
        $stats = [
            'total' => Nasabah::count(),
            'active' => Nasabah::where('status', 'active')->count(),
            'inactive' => Nasabah::where('status', 'inactive')->count(),
            'male' => Nasabah::where('gender', 'Laki-laki')->count(),
            'female' => Nasabah::where('gender', 'Perempuan')->count(),
            'with_loans' => Nasabah::has('loans')->count(),
        ];

        return view('admin.nasabah.index', compact('nasabah', 'stats'));
    }

    private function getUserValidationRules($userId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:admin,finance,collector,nasabah',
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
