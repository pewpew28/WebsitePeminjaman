<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents(public_path('data/dummy.json')), true);
    }

    public function dashboard()
    {
        $data = $this->data;
        return view('admin.dashboard', compact('data'));
    }

    public function userIndex()
{
    $title = 'Manajemen Users';

    // Assume $this->data['users'] is a Collection
    $allUsers = collect($this->data['users']);

    // Manually paginate
    $perPage = 5;
    $currentPage = request()->get('page', 1);
    $pagedData = $allUsers->slice(($currentPage - 1) * $perPage, $perPage);
    $data = new LengthAwarePaginator(
        $pagedData,
        $allUsers->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Recalculate stats if needed
    $stats = [
        'total_users' => $allUsers->count(),
        'admin_count' => $allUsers->where('role', 'admin')->count(),
        'collector_count' => $allUsers->where('role', 'collector')->count(),
        'nasabah_count' => $allUsers->where('role', 'nasabah')->count(),
        'finance_count' => $allUsers->where('role', 'finance')->count(),
    ];

    return view('admin.users.index', compact('title', 'data', 'stats'));
}

    public function userCreate()
    {
        $title = 'Tambah User';
        $roles = ['admin', 'finance', 'collector', 'nasabah'];
        return view('admin.users.create', compact('title', 'roles'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            'role' => 'required|in:admin,finance,collector,nasabah',
            'address' => 'required_if:role,nasabah,collector',
            'ktp' => 'required_if:role,nasabah',
            'area' => 'required_if:role,collector',
        ]);

        // Simulasi penyimpanan data
        // Dalam implementasi nyata, simpan ke database
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function userShow($id)
    {
        $title = 'Detail User';
        $user = collect($this->data['users'])->firstWhere('id', $id);
        
        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        // Get additional data based on user role
        $additionalData = [];
        
        if ($user['role'] === 'nasabah') {
            $additionalData['loans'] = array_filter($this->data['loans'], 
                fn($loan) => $loan['nasabah_id'] === $id
            );
            $additionalData['installments'] = array_filter($this->data['installments'],
                fn($installment) => in_array($installment['loan_id'], array_column($additionalData['loans'], 'id'))
            );
        } elseif ($user['role'] === 'collector') {
            $additionalData['tasks'] = array_filter($this->data['collection_tasks'],
                fn($task) => $task['collector_id'] === $id
            );
            $additionalData['assigned_loans'] = array_filter($this->data['loans'],
                fn($loan) => $loan['collector_id'] === $id
            );
        }
        
        return view('admin.users.show', compact('title', 'user', 'additionalData'));
    }

    public function userEdit($id)
    {
        $title = 'Edit User';
        $user = collect($this->data['users'])->firstWhere('id', $id);
        
        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }
        
        $roles = ['admin', 'finance', 'collector', 'nasabah'];
        return view('admin.users.edit', compact('title', 'user', 'roles'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = collect($this->data['users'])->firstWhere('id', $id);
        
        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'role' => 'required|in:admin,finance,collector,nasabah',
            'address' => 'required_if:role,nasabah,collector',
            'ktp' => 'required_if:role,nasabah',
            'area' => 'required_if:role,collector',
        ]);

        // Simulasi update data
        // Dalam implementasi nyata, update ke database
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate');
    }

    public function userDestroy($id)
    {
        $user = collect($this->data['users'])->firstWhere('id', $id);
        
        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        // Simulasi hapus data
        // Dalam implementasi nyata, hapus dari database
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    // Loan Management Methods
    public function loanIndex()
    {
        $title = 'Manajemen Pinjaman';
        $loans = $this->data['loans'];
        
        // Enrich loan data with nasabah info
        foreach ($loans as &$loan) {
            $nasabah = collect($this->data['users'])->firstWhere('id', $loan['nasabah_id']);
            $loan['nasabah_name'] = $nasabah['name'] ?? 'Unknown';
            
            $collector = collect($this->data['users'])->firstWhere('id', $loan['collector_id']);
            $loan['collector_name'] = $collector['name'] ?? 'Unassigned';
        }
        
        $stats = [
            'total_loans' => count($loans),
            'active_loans' => count(array_filter($loans, fn($loan) => $loan['loan_status'] === 'active')),
            'total_amount' => array_sum(array_column($loans, 'loan_amount')),
        ];
        
        return view('admin.loans.index', compact('title', 'loans', 'stats'));
    }

    public function loanShow($id)
    {
        $title = 'Detail Pinjaman';
        $loan = collect($this->data['loans'])->firstWhere('id', $id);
        
        if (!$loan) {
            abort(404, 'Pinjaman tidak ditemukan');
        }

        // Get related data
        $nasabah = collect($this->data['users'])->firstWhere('id', $loan['nasabah_id']);
        $collector = collect($this->data['users'])->firstWhere('id', $loan['collector_id']);
        $installments = array_filter($this->data['installments'], 
            fn($installment) => $installment['loan_id'] === $id
        );
        
        return view('admin.loans.show', compact('title', 'loan', 'nasabah', 'collector', 'installments'));
    }

    // Collection Tasks Methods
    public function taskIndex()
    {
        $title = 'Manajemen Tugas Penagihan';
        $tasks = $this->data['collection_tasks'];
        
        // Enrich task data
        foreach ($tasks as &$task) {
            $collector = collect($this->data['users'])->firstWhere('id', $task['collector_id']);
            $task['collector_name'] = $collector['name'] ?? 'Unknown';
            
            $nasabah = collect($this->data['users'])->firstWhere('id', $task['nasabah_id']);
            $task['nasabah_name'] = $nasabah['name'] ?? 'Unknown';
        }
        
        $stats = [
            'total_tasks' => count($tasks),
            'completed_tasks' => count(array_filter($tasks, fn($task) => $task['status'] === 'completed')),
            'pending_tasks' => count(array_filter($tasks, fn($task) => $task['status'] === 'pending')),
            'in_progress_tasks' => count(array_filter($tasks, fn($task) => $task['status'] === 'in_progress')),
        ];
        
        return view('admin.tasks.index', compact('title', 'tasks', 'stats'));
    }

    // Reports Methods
    public function reports()
    {
        $title = 'Laporan';
        $overview = $this->data['overview'];
        
        return view('admin.reports.index', compact('title', 'overview'));
    }

    // API Methods for AJAX requests
    public function getUsersApi(Request $request)
    {
        $users = $this->data['users'];
        
        if ($request->has('role')) {
            $users = array_filter($users, fn($user) => $user['role'] === $request->role);
        }
        
        return response()->json($users);
    }

    public function getOverviewApi()
    {
        return response()->json($this->data['overview']);
    }
}