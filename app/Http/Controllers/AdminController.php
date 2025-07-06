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

    public function __construct(
        AdminDashboardService $dashboardService,
    ) {
        $this->dashboardService = $dashboardService;    
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
}
