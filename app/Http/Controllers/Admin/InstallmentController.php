<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Installment\StoreInstallmentRequest;
use App\Http\Requests\Installment\UpdateInstallmentRequest;
use App\Http\Requests\Installment\RecordPaymentRequest;
use App\Services\InstallmentService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class InstallmentController extends Controller
{
    protected $installmentService;

    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    public function index(Request $request): View
    {
        try {
            $filters = $request->query();
            $installments = $this->installmentService->getAllInstallments($filters);
            return view('admin.installments.index', compact('installments'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch installments: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.installments.create');
    }

    public function show($id): View
    {
        try {
            $installment = $this->installmentService->getInstallmentById($id);
            return view('admin.installments.show', compact('installment'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch installment: ' . $e->getMessage());
        }
    }

    public function store(StoreInstallmentRequest $request): RedirectResponse
    {
        try {
            $installment = $this->installmentService->createInstallment($request->validated());
            return redirect()->route('admin.installments.index')->with('success', 'Installment created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create installment: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        try {
            $installment = $this->installmentService->getInstallmentById($id);
            return view('admin.installments.edit', compact('installment'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch installment: ' . $e->getMessage());
        }
    }

    public function update(UpdateInstallmentRequest $request, $id): RedirectResponse
    {
        try {
            $installment = $this->installmentService->updateInstallment($id, $request->validated());
            return redirect()->route('admin.installments.index')->with('success', 'Installment updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update installment: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->installmentService->deleteInstallment($id);
            return redirect()->route('admin.installments.index')->with('success', 'Installment deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete installment: ' . $e->getMessage());
        }
    }

    public function restore($id): RedirectResponse
    {
        try {
            $installment = $this->installmentService->restoreInstallment($id);
            return redirect()->route('admin.installments.index')->with('success', 'Installment restored successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to restore installment: ' . $e->getMessage());
        }
    }

    public function recordPayment(RecordPaymentRequest $request, $id): RedirectResponse
    {
        try {
            $data = $request->validated();
            $installment = $this->installmentService->recordPayment($id, $data, $data['payer_id']);
            return redirect()->route('admin.installments.show', $id)->with('success', 'Payment recorded successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }

    public function uploadPaymentProof(Request $request, $id): RedirectResponse
    {
        try {
            $request->validate(['file' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
            $media = $this->installmentService->uploadPaymentProof($id, $request->file('file'));
            return redirect()->route('admin.installments.show', $id)->with('success', 'Payment proof uploaded successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to upload payment proof: ' . $e->getMessage());
        }
    }
}