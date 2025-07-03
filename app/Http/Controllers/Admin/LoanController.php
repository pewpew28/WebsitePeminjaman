<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\StoreLoanRequest;
use App\Http\Requests\Loan\UpdateLoanRequest;
use App\Http\Requests\Loan\ApproveLoanRequest;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

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
}