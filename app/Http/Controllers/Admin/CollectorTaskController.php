<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectorTask;
use App\Models\User;
use App\Models\Nasabah;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CollectorTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $collectors = User::with([
                'collectorTasks' => function ($query) {
                    $query->with(['nasabah']);
                },
                'collectorTasks.nasabah.loans'
            ])->where('role', 'collector')->get();

            // Hitung statistik untuk dashboard
            $totalCollectors = $collectors->count();
            $totalTasks = $collectors->sum(function ($collector) {
                return $collector->collectorTasks->count();
            });
            $activeTasks = $collectors->sum(function ($collector) {
                return $collector->collectorTasks->where('status', 'active')->count();
            });
            $completedTasks = $collectors->sum(function ($collector) {
                return $collector->collectorTasks->where('status', 'completed')->count();
            });
            $totalPendapatan = $collectors->sum(function ($collector) {
                return $collector->collectorTasks->sum('amount_collected_during_task');
            });

            $statistics = [
                'total_collectors' => $totalCollectors,
                'total_tasks' => $totalTasks,
                'active_tasks' => $activeTasks,
                'completed_tasks' => $completedTasks,
                'total_pendapatan' => $totalPendapatan,
            ];

            return view('admin.collectorTask.index', compact('collectors', 'statistics'));
        } catch (\Exception $e) {
            Log::error('Error in CollectorTaskController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $collector)
    {
        try {
            $collector->load([
                'collectorTasks' => function ($query) {
                    $query->with(['nasabah', 'installment'])
                        ->orderBy('due_date', 'asc');
                }
            ]);

            // Statistik untuk collector spesifik
            $statistics = [
                'total_tasks' => $collector->collectorTasks->count(),
                'active_tasks' => $collector->collectorTasks->where('status', 'active')->count(),
                'completed_tasks' => $collector->collectorTasks->where('status', 'completed')->count(),
                'pending_tasks' => $collector->collectorTasks->where('status', 'pending')->count(),
                'total_collected' => $collector->collectorTasks->sum('amount_collected_during_task'),
                'average_collection' => $collector->collectorTasks->where('amount_collected_during_task', '>', 0)->avg('amount_collected_during_task'),
            ];

            // Fetch all collectors for the filter dropdown
            $collectors = User::where('role', 'collector')->get();

            return view('admin.collectorTask.show', compact('collector', 'statistics', 'collectors'));
        } catch (\Exception $e) {
            Log::error('Error in CollectorTaskController@show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat detail collector.');
        }
    }
}
