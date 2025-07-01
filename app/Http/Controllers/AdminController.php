<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\Nasabah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. RINGKASAN KEUANGAN
        $financialSummary = [
            'total_active_loans' => $this->getTotalActiveLoans(),
            'total_payments_received' => $this->getTotalPaymentsReceived(),
            'total_overdue' => $this->getTotalOverdue(),
            'monthly_income' => $this->getMonthlyIncome(),
        ];

        // 2. STATISTIK NASABAH & PINJAMAN
        $customerStats = [
            'new_customers_count' => $this->getNewCustomersThisMonth(),
            'new_customers_growth' => $this->getNewCustomersGrowth(),
            'new_loans_count' => $this->getNewLoansThisMonth(),
            'new_loans_growth' => $this->getNewLoansGrowth(),
            'total_customers' => $this->getTotalCustomers(),
            'active_customers_percentage' => $this->getActiveCustomersPercentage(),
        ];

        // 3. AKTIVITAS TERBARU
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact(
            'financialSummary',
            'customerStats',
            'recentActivities'
        ));
    }

    public function userIndex(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('last_login_at');
            } else {
                $query->whereNull('last_login_at');
            }
        }

        // Order by created_at descending
        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(10);

        // Statistik untuk summary cards
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'finance' => User::where('role', 'finance')->count(),
            'collector' => User::where('role', 'collector')->count(),
            'nasabah' => User::where('role', 'nasabah')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function userCreate()
    {
        return view('admin.users.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:admin,finance,collector,nasabah',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'email_verified_at' => now(), // Auto verify for admin created users
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }
    public function userShow(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // RINGKASAN KEUANGAN METHODS
    private function getTotalActiveLoans()
    {
        return Loan::whereIn('status', ['active', 'disbursed'])
            ->sum('loan_amount');
    }

    private function getTotalPaymentsReceived()
    {
        // Total pembayaran yang diterima bulan ini dari installments
        return Installment::where('status', 'paid')
            ->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('amount_paid');
    }

    private function getTotalOverdue()
    {
        return Loan::whereIn('status', ['active', 'disbursed'])
            ->where('end_date', '<', Carbon::now())
            ->sum('remaining_principal');
    }

    private function getMonthlyIncome()
    {
        // Pendapatan bunga dari installments yang dibayar bulan ini
        return Installment::where('status', 'paid')
            ->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('interest_amount');
    }

    // STATISTIK NASABAH & PINJAMAN METHODS
    private function getNewCustomersThisMonth()
    {
        return Nasabah::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    private function getNewCustomersGrowth()
    {
        $thisMonth = $this->getNewCustomersThisMonth();
        $lastMonth = Nasabah::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) return 0;
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 0);
    }

    private function getNewLoansThisMonth()
    {
        return Loan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    private function getNewLoansGrowth()
    {
        $thisMonth = $this->getNewLoansThisMonth();
        $lastMonth = Loan::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        if ($lastMonth == 0) return 0;
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 0);
    }

    private function getTotalCustomers()
    {
        return Nasabah::count();
    }

    private function getActiveCustomersPercentage()
    {
        $totalCustomers = $this->getTotalCustomers();
        // Nasabah dianggap aktif jika memiliki pinjaman yang masih berjalan
        $activeCustomers = Nasabah::whereHas('loans', function ($query) {
            $query->whereIn('status', ['active', 'disbursed']);
        })->count();

        if ($totalCustomers == 0) return 0;
        return round(($activeCustomers / $totalCustomers) * 100, 0);
    }

    // AKTIVITAS TERBARU METHOD
    private function getRecentActivities()
    {
        $activities = collect();

        // Nasabah baru (5 terakhir)
        $newCustomers = Nasabah::latest()
            ->take(5)
            ->get()
            ->map(function ($nasabah) {
                return [
                    'type' => 'new_customer',
                    'icon_class' => 'bg-green-100 text-green-600',
                    'title' => 'Nasabah baru: ' . $nasabah->name,
                    'time' => $nasabah->created_at->diffForHumans(),
                    'created_at' => $nasabah->created_at
                ];
            });

        // Pinjaman disetujui (5 terakhir)
        $approvedLoans = Loan::with('nasabah')
            ->whereIn('status', ['approved', 'disbursed'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($loan) {
                return [
                    'type' => 'loan_approved',
                    'icon_class' => 'bg-blue-100 text-blue-600',
                    'title' => 'Pinjaman disetujui: Rp ' . number_format($loan->loan_amount / 1000000, 1) . 'jt - ' . $loan->nasabah->name,
                    'time' => $loan->updated_at->diffForHumans(),
                    'created_at' => $loan->updated_at
                ];
            });

        // Pembayaran terbaru berdasarkan installments yang sudah dibayar (5 terakhir)
        $recentPayments = Installment::with('loan.nasabah')
            ->where('status', 'paid')
            ->whereNotNull('payment_date')
            ->where('amount_paid', '>', 0)
            ->latest('payment_date')
            ->take(5)
            ->get()
            ->map(function ($installment) {
                return [
                    'type' => 'payment_received',
                    'icon_class' => 'bg-purple-100 text-purple-600',
                    'title' => 'Pembayaran diterima: Rp ' . number_format($installment->amount_paid / 1000, 0) . 'rb - ' . $installment->loan->nasabah->name,
                    'time' => $installment->payment_date->diffForHumans(),
                    'created_at' => $installment->payment_date
                ];
            });

        // Gabungkan semua aktivitas dan urutkan berdasarkan waktu
        $activities = $activities->concat($newCustomers)
            ->concat($approvedLoans)
            ->concat($recentPayments)
            ->sortByDesc('created_at')
            ->take(10); // Ambil 10 aktivitas terakhir

        return $activities->values();
    }
}
