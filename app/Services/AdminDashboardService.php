<?php

namespace App\Services;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\Nasabah;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    public function getFinancialSummary(): array
    {
        return [
            'total_active_loans' => $this->getTotalActiveLoans(),
            'total_payments_received' => $this->getTotalPaymentsReceived(),
            'total_overdue' => $this->getTotalOverdue(),
            'monthly_income' => $this->getMonthlyIncome(),
        ];
    }

    public function getCustomerStats(): array
    {
        return [
            'new_customers_count' => $this->getNewCustomersThisMonth(),
            'new_customers_growth' => $this->getNewCustomersGrowth(),
            'new_loans_count' => $this->getNewLoansThisMonth(),
            'new_loans_growth' => $this->getNewLoansGrowth(),
            'total_customers' => $this->getTotalCustomers(),
            'active_customers_percentage' => $this->getActiveCustomersPercentage(),
        ];
    }

    public function getRecentActivities(): Collection
    {
        return collect()
            ->merge($this->getNewCustomerActivities())
            ->merge($this->getApprovedLoanActivities())
            ->merge($this->getRecentPaymentActivities())
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }

    private function getTotalActiveLoans(): float
    {
        return Loan::whereIn('status', ['active', 'disbursed'])
            ->sum('loan_amount');
    }

    private function getTotalPaymentsReceived(): float
    {
        $currentMonth = Carbon::now();
        
        return Installment::where('status', 'paid')
            ->whereMonth('payment_date', $currentMonth->month)
            ->whereYear('payment_date', $currentMonth->year)
            ->sum('amount_paid');
    }

    private function getTotalOverdue(): float
    {
        return Loan::whereIn('status', ['active', 'disbursed'])
            ->where('end_date', '<', Carbon::now())
            ->sum('remaining_principal');
    }

    private function getMonthlyIncome(): float
    {
        $currentMonth = Carbon::now();
        
        return Installment::where('status', 'paid')
            ->whereMonth('payment_date', $currentMonth->month)
            ->whereYear('payment_date', $currentMonth->year)
            ->sum('interest_amount');
    }

    private function getNewCustomersThisMonth(): int
    {
        $currentMonth = Carbon::now();
        
        return Nasabah::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
    }

    private function getNewCustomersGrowth(): int
    {
        $thisMonth = $this->getNewCustomersThisMonth();
        $lastMonth = $this->getCustomersCountForMonth(Carbon::now()->subMonth());

        return $this->calculateGrowthPercentage($thisMonth, $lastMonth);
    }

    private function getNewLoansThisMonth(): int
    {
        $currentMonth = Carbon::now();
        
        return Loan::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
    }

    private function getNewLoansGrowth(): int
    {
        $thisMonth = $this->getNewLoansThisMonth();
        $lastMonth = $this->getLoansCountForMonth(Carbon::now()->subMonth());

        return $this->calculateGrowthPercentage($thisMonth, $lastMonth);
    }

    private function getTotalCustomers(): int
    {
        return Nasabah::count();
    }

    private function getActiveCustomersPercentage(): int
    {
        $totalCustomers = $this->getTotalCustomers();
        
        if ($totalCustomers === 0) {
            return 0;
        }

        $activeCustomers = Nasabah::whereHas('loans', function ($query) {
            $query->whereIn('status', ['active', 'disbursed']);
        })->count();

        return round(($activeCustomers / $totalCustomers) * 100, 0);
    }

    private function getNewCustomerActivities(): Collection
    {
        return Nasabah::latest()
            ->take(5)
            ->get()
            ->map(fn($nasabah) => [
                'type' => 'new_customer',
                'icon_class' => 'bg-green-100 text-green-600',
                'title' => 'Nasabah baru: ' . $nasabah->name,
                'time' => $nasabah->created_at->diffForHumans(),
                'created_at' => $nasabah->created_at
            ]);
    }

    private function getApprovedLoanActivities(): Collection
    {
        return Loan::with('nasabah')
            ->whereIn('status', ['approved', 'disbursed'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($loan) => [
                'type' => 'loan_approved',
                'icon_class' => 'bg-blue-100 text-blue-600',
                'title' => sprintf(
                    'Pinjaman disetujui: Rp %s - %s',
                    number_format($loan->loan_amount / 1000000, 1) . 'jt',
                    $loan->nasabah->name
                ),
                'time' => $loan->updated_at->diffForHumans(),
                'created_at' => $loan->updated_at
            ]);
    }

    private function getRecentPaymentActivities(): Collection
    {
        return Installment::with('loan.nasabah')
            ->where('status', 'paid')
            ->whereNotNull('payment_date')
            ->where('amount_paid', '>', 0)
            ->latest('payment_date')
            ->take(5)
            ->get()
            ->map(fn($installment) => [
                'type' => 'payment_received',
                'icon_class' => 'bg-purple-100 text-purple-600',
                'title' => sprintf(
                    'Pembayaran diterima: Rp %s - %s',
                    number_format($installment->amount_paid / 1000, 0) . 'rb',
                    $installment->loan->nasabah->name
                ),
                'time' => $installment->payment_date->diffForHumans(),
                'created_at' => $installment->payment_date
            ]);
    }

    private function getCustomersCountForMonth(Carbon $month): int
    {
        return Nasabah::whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->count();
    }

    private function getLoansCountForMonth(Carbon $month): int
    {
        return Loan::whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->count();
    }

    private function calculateGrowthPercentage(int $current, int $previous): int
    {
        if ($previous === 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 0);
    }
}