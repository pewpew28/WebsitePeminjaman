<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\StoreLoanRequest;
use App\Http\Requests\Loan\UpdateLoanRequest;
use App\Http\Requests\Loan\ApproveLoanRequest;
use App\Models\CollectorTask;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index(Request $request): View
    {
        try {
            $filters = $request->query();
            $loans = $this->loanService->getAllLoans($filters);
            return view('admin.loans.index', compact('loans'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch loans: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.loans.create');
    }

    public function show($id): View
    {
        try {
            $loan = $this->loanService->getLoanById($id);
            return view('admin.loans.show', compact('loan'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch loan: ' . $e->getMessage());
        }
    }

    public function store(StoreLoanRequest $request): RedirectResponse
    {
        // dd($request->all());
        try {
            $loan = $this->loanService->createLoan($request->validated());
            return redirect()->route('admin.loans.index')->with('success', 'Loan created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create loan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        try {
            $loan = $this->loanService->getLoanById($id);
            return view('admin.loans.edit', compact('loan'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch loan: ' . $e->getMessage());
        }
    }

    public function update(UpdateLoanRequest $request, $id): RedirectResponse
    {
        try {
            $loan = $this->loanService->updateLoan($id, $request->validated());
            return redirect()->route('admin.loans.index')->with('success', 'Loan updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update loan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->loanService->deleteLoan($id);
            return redirect()->route('admin.loans.index')->with('success', 'Loan deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete loan: ' . $e->getMessage());
        }
    }

    public function restore($id): RedirectResponse
    {
        try {
            $loan = $this->loanService->restoreLoan($id);
            return redirect()->route('admin.loans.index')->with('success', 'Loan restored successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to restore loan: ' . $e->getMessage());
        }
    }

    public function approve(ApproveLoanRequest $request, $id): RedirectResponse
    {
        try {
            $loan = $this->loanService->approveLoan($id, $request->validated()['approver_id']);
            return redirect()->route('admin.loans.index')->with('success', 'Loan approved successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to approve loan: ' . $e->getMessage());
        }
    }

    public function assignment($id)
    {
        $collector = User::where('role', 'collector')->get();
        return $collector;
    }
    public function assignmentStore(Request $request, $id)
    {
        try {
            $request->validate([
                'collector_id' => 'required|exists:users,id',
                'old_collector_id' => 'nullable|exists:users,id',
                'new_collector_id' => 'nullable|exists:users,id',
            ]);

            $loan = Loan::with([
                'installments' => fn($q) => $q->where('status', '!=', 'paid'),
                'nasabah'
            ])->findOrFail($id);

            // Determine the target collector ID
            $targetCollectorId = $request->new_collector_id ?? $request->collector_id;

            // Check if collector role is valid
            $collector = User::where('id', $targetCollectorId)
                ->where('role', 'collector')
                ->firstOrFail();

            // Handle reassignment scenario
            if (
                $request->old_collector_id && $request->new_collector_id &&
                $request->old_collector_id !== $request->new_collector_id
            ) {

                // Update existing tasks for the old collector
                CollectorTask::where('collector_id', $request->old_collector_id)
                    ->whereIn('installment_id', $loan->installments->pluck('id'))
                    ->where('status', 'active') // Only update pending tasks
                    ->update([
                        'collector_id' => $request->new_collector_id,
                        'assigned_date' => now(),
                        'updated_at' => now()
                    ]);

                // Update installments collector_id
                foreach ($loan->installments as $installment) {
                    $installment->update([
                        'collector_id' => $request->new_collector_id
                    ]);
                }

                // Update loan with new collector assignment
                $loan->update([
                    'collector_id' => $request->new_collector_id,
                ]);

                $message = 'Loan successfully reassigned from previous collector to ' . $collector->name;
            } else {
                // Normal assignment (new assignment)
                // Update loan with collector assignment
                $loan->update([
                    'collector_id' => $targetCollectorId,
                ]);

                // Create new tasks for each unpaid installment
                foreach ($loan->installments as $installment) {
                    // Check if task already exists to avoid duplicates
                    $existingTask = CollectorTask::where('collector_id', $targetCollectorId)
                        ->where('installment_id', $installment->id)
                        ->first();

                    if (!$existingTask) {
                        CollectorTask::create([
                            'collector_id' => $targetCollectorId,
                            'nasabah_id' => $loan->nasabah->id,
                            'installment_id' => $installment->id,
                            'assigned_date' => now(),
                            'due_date' => $installment->due_date,
                            'status' => 'active',
                        ]);
                    }

                    $installment->update([
                        'collector_id' => $targetCollectorId
                    ]);
                }

                $message = 'Loan successfully assigned to ' . $collector->name;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loan or collector not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign loan: ' . $e->getMessage()
            ], 500);
        }
    }
}
