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
                    $query->with(['nasabah', 'loan']);
                },
                'collectorTasks.nasabah.loans'
            ])->where('role', 'collector')->get();

            // Hitung statistik untuk dashboard
            $totalCollectors = $collectors->count();
            $totalTasks = $collectors->sum(function($collector) {
                return $collector->collectorTasks->count();
            });
            $activeTasks = $collectors->sum(function($collector) {
                return $collector->collectorTasks->where('status', 'active')->count();
            });
            $completedTasks = $collectors->sum(function($collector) {
                return $collector->collectorTasks->where('status', 'completed')->count();
            });
            $totalPendapatan = $collectors->sum(function($collector) {
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $collectors = User::where('role', 'collector')->get();
            $nasabahs = Nasabah::with('loans')->get();
            $loans = Loan::with('nasabah')->where('status', 'active')->get();

            return view('admin.collectorTask.create', compact('collectors', 'nasabahs', 'loans'));
        } catch (\Exception $e) {
            Log::error('Error in CollectorTaskController@create: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'nasabah_id' => 'required|exists:nasabahs,id',
            'loan_id' => 'nullable|exists:loans,id',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'status' => 'required|in:pending,active,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $collectorTask = CollectorTask::create([
                'collector_id' => $request->collector_id,
                'nasabah_id' => $request->nasabah_id,
                'loan_id' => $request->loan_id,
                'assigned_date' => $request->assigned_date,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.collector-tasks.index')
                ->with('success', 'Task collector berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CollectorTaskController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.');
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
                $query->with(['nasabah', 'loan'])->latest();
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectorTask $collectorTask)
    {
        try {
            $collectors = User::where('role', 'collector')->get();
            $nasabahs = Nasabah::with('loans')->get();
            $loans = Loan::with('nasabah')->where('status', 'active')->get();

            return view('admin.collectorTask.edit', compact('collectorTask', 'collectors', 'nasabahs', 'loans'));
        } catch (\Exception $e) {
            Log::error('Error in CollectorTaskController@edit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollectorTask $collectorTask)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'nasabah_id' => 'required|exists:nasabahs,id',
            'loan_id' => 'nullable|exists:loans,id',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'status' => 'required|in:pending,active,completed,cancelled',
            'notes' => 'nullable|string',
            'amount_collected_during_task' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $collectorTask->update([
                'collector_id' => $request->collector_id,
                'nasabah_id' => $request->nasabah_id,
                'loan_id' => $request->loan_id,
                'assigned_date' => $request->assigned_date,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'notes' => $request->notes,
                'amount_collected_during_task' => $request->amount_collected_during_task,
            ]);

            DB::commit();

            return redirect()->route('admin.collector-tasks.index')
                ->with('success', 'Task collector berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CollectorTaskController@update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollectorTask $collectorTask)
    {
        try {
            DB::beginTransaction();

            $collectorTask->delete();

            DB::commit();

            return redirect()->route('admin.collector-tasks.index')
                ->with('success', 'Task collector berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CollectorTaskController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /**
     * Show form to assign new task to collector.
     */
    public function assign(User $collector)
    {
        try {
            $nasabahs = Nasabah::with('loans')->get();
            $loans = Loan::with('nasabah')->where('status', 'active')->get();

            return view('admin.collectorTask.assign', compact('collector', 'nasabahs', 'loans'));
        } catch (\Exception $e) {
            Log::error('Error in CollectorTaskController@assign: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat form assign.');
        }
    }

    /**
     * Store assigned task to collector.
     */
    public function storeAssignment(Request $request, User $collector)
    {
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'loan_id' => 'nullable|exists:loans,id',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            CollectorTask::create([
                'collector_id' => $collector->id,
                'nasabah_id' => $request->nasabah_id,
                'loan_id' => $request->loan_id,
                'assigned_date' => $request->assigned_date,
                'due_date' => $request->due_date,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.collector-tasks.show', $collector)
                ->with('success', 'Task berhasil ditugaskan ke collector.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CollectorTaskController@storeAssignment: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menugaskan task.');
        }
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, CollectorTask $collectorTask)
    {
        $request->validate([
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $collectorTask->update([
                'status' => $request->status,
            ]);

            // Jika status completed, set actual_visit_date
            if ($request->status === 'completed') {
                $collectorTask->update([
                    'actual_visit_date' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status task berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in CollectorTaskController@updateStatus: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui status.');
        }
    }

    /**
     * Get tasks data for AJAX requests.
     */
    public function getTasksData(Request $request)
    {
        try {
            $tasks = CollectorTask::with(['collector', 'nasabah', 'loan'])
                ->when($request->collector_id, function($query) use ($request) {
                    return $query->where('collector_id', $request->collector_id);
                })
                ->when($request->status, function($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tasks
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CollectorTaskController@getTasksData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data tasks.'
            ], 500);
        }
    }
}