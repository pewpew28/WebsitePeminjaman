<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\CollectorTask;
use App\Models\Installment;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CollectorController extends Controller
{
    public function index()
    {
        try {
            $collectorId = Auth::user()->id;
            $dashboardData = $this->buildDashboardData($collectorId);

            return view('collector.dashboard', compact('dashboardData'));
        } catch (\Exception $e) {
            Log::error('Error in CollectorController@index: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('collector.dashboard', [
                'dashboardData' => $this->getEmptyDashboardData()
            ]);
        }
    }

    /**
     * Build data dashboard yang sederhana
     */
    private function buildDashboardData($collectorId)
    {
        $today = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        // Ambil semua task yang perlu dikerjakan hari ini (termasuk overdue)
        $todayTasks = $this->getTodayTasks($collectorId, $today);

        // Pisahkan task berdasarkan status due date
        $todayDueTasks = $todayTasks->filter(function ($task) use ($today) {
            return $task->due_date->startOfDay()->equalTo($today);
        });

        $overdueTasks = $todayTasks->filter(function ($task) use ($today) {
            return $task->due_date->startOfDay()->lessThan($today);
        });

        // Ambil task yang sudah complete hari ini
        $completedToday = $this->getCompletedTasksToday($collectorId, $today, $endOfDay);

        // Hitung pendapatan hari ini
        $todayRevenue = $this->getTodayRevenue($collectorId, $today, $endOfDay);

        // Statistik sederhana
        $stats = [
            'today_tasks_count' => $todayTasks->count(),
            'today_due_tasks_count' => $todayDueTasks->count(),
            'overdue_tasks_count' => $overdueTasks->count(),
            'completed_today_count' => $completedToday->count(),
            'today_revenue' => $todayRevenue,
            'completion_rate_today' => $this->calculateTodayCompletionRate($todayTasks, $completedToday)
        ];

        return [
            'stats' => $stats,
            'today_tasks' => $todayTasks,
            'today_due_tasks' => $todayDueTasks,
            'overdue_tasks' => $overdueTasks,
            'completed_today' => $completedToday
        ];
    }

    /**
     * Ambil semua task yang perlu dikerjakan hari ini (termasuk overdue)
     */
    private function getTodayTasks($collectorId, $today)
    {
        return CollectorTask::where('collector_id', $collectorId)
            ->whereDate('due_date', '<=', $today) // Semua task sampai hari ini
            ->where('status', '!=', 'completed')
            ->with(['nasabah', 'installment'])
            ->select([
                'id',
                'collector_id',
                'nasabah_id',
                'installment_id',
                'status',
                'due_date',
                'assigned_date',
                'notes',
                'amount_collected_during_task'
            ])
            ->orderBy('due_date', 'asc') // Prioritas overdue dulu
            ->get();
    }

    /**
     * Ambil task yang sudah diselesaikan hari ini
     */
    private function getCompletedTasksToday($collectorId, $today, $endOfDay)
    {
        return CollectorTask::where('collector_id', $collectorId)
            ->where('status', 'completed')
            ->whereBetween('actual_visit_date', [$today, $endOfDay])
            ->with(['nasabah', 'installment'])
            ->select([
                'id',
                'collector_id',
                'nasabah_id',
                'installment_id',
                'status',
                'due_date',
                'actual_visit_date',
                'amount_collected_during_task'
            ])
            ->orderBy('actual_visit_date', 'desc')
            ->get();
    }

    /**
     * Hitung total pendapatan hari ini
     */
    private function getTodayRevenue($collectorId, $today, $endOfDay)
    {
        return CollectorTask::where('collector_id', $collectorId)
            ->where('status', 'completed')
            ->whereBetween('actual_visit_date', [$today, $endOfDay])
            ->sum('amount_collected_during_task') ?? 0;
    }

    /**
     * Hitung completion rate hari ini
     */
    private function calculateTodayCompletionRate($todayTasks, $completedToday)
    {
        $totalTodayTasks = $todayTasks->count() + $completedToday->count();

        if ($totalTodayTasks === 0) {
            return 0;
        }

        return round(($completedToday->count() / $totalTodayTasks) * 100, 1);
    }

    /**
     * Data kosong untuk fallback
     */
    private function getEmptyDashboardData()
    {
        return [
            'stats' => [
                'today_tasks_count' => 0,
                'today_due_tasks_count' => 0,
                'overdue_tasks_count' => 0,
                'completed_today_count' => 0,
                'today_revenue' => 0,
                'completion_rate_today' => 0
            ],
            'today_tasks' => collect(),
            'today_due_tasks' => collect(),
            'overdue_tasks' => collect(),
            'completed_today' => collect()
        ];
    }

    /**
     * Method untuk mendapatkan statistik dalam format JSON
     */
    public function getStatsJson()
    {
        try {
            $collectorId = Auth::user()->id;
            $dashboardData = $this->buildDashboardData($collectorId);

            return response()->json([
                'success' => true,
                'data' => $dashboardData['stats']
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CollectorController@getStatsJson: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics'
            ], 500);
        }
    }

    public function dataNasabah($nasabahId)
    {
        $nasabah = Nasabah::with(['loans.installments'])
            ->findOrFail($nasabahId);

        $latestLoan = $nasabah->loans()
            ->whereIn('status', ['active', 'partial']) // hanya pinjaman aktif
            ->latest('start_date')
            ->with('installments')
            ->first();

        $loanData = null;
        $recentPayments = [];

        if ($latestLoan) {
            $loanData = [
                'id' => $latestLoan->id,
                'total_amount' => $latestLoan->loan_amount,
                'remaining_amount' => $latestLoan->remaining_principal + $latestLoan->remaining_interest + $latestLoan->remaining_fines,
                'loan_term' => $latestLoan->loan_term,
                'status' => $latestLoan->status,
            ];

            // Ambil 5 pembayaran terakhir
            $recentPayments = Installment::where('loan_id', $latestLoan->id)
                ->whereNotNull('payment_date')
                ->orderByDesc('payment_date')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => optional($item->payment_date)->format('Y-m-d H:i'),
                        'method' => $item->payment_method,
                        'amount' => $item->amount_paid,
                    ];
                });
        }

        return response()->json([
            'success' => true,
            'nasabah' => [
                'id' => $nasabah->id,
                'name' => $nasabah->name,
                'address' => $nasabah->address,
                'phone_number' => $nasabah->phone_number,
                'email' => $nasabah->email,
            ],
            'loan' => $loanData,
            'recent_payments' => $recentPayments,
        ]);
    }
}
