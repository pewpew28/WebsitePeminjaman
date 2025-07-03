<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nasabah\StoreNasabahRequest;
use App\Http\Requests\Nasabah\UpdateNasabahRequest;
use App\Services\NasabahService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class NasabahController extends Controller
{
    protected $nasabahService;

    public function __construct(NasabahService $nasabahService)
    {
        $this->nasabahService = $nasabahService;
    }

    public function index(Request $request): View
    {
        try {
            $filters = $request->query();
            $nasabahs = $this->nasabahService->getAllNasabahs($filters);
            return view('admin.nasabahs.index', compact('nasabahs'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch nasabahs: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.nasabahs.create');
    }

    public function show($id): View
    {
        try {
            $nasabah = $this->nasabahService->getNasabahById($id);
            return view('admin.nasabahs.show', compact('nasabah'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch nasabah: ' . $e->getMessage());
        }
    }

    public function store(StoreNasabahRequest $request): RedirectResponse
    {
        try {
            $nasabah = $this->nasabahService->createNasabah($request->validated());
            return redirect()->route('admin.nasabahs.index')->with('success', 'Nasabah created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create nasabah: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        try {
            $nasabah = $this->nasabahService->getNasabahById($id);
            return view('admin.nasabahs.edit', compact('nasabah'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch nasabah: ' . $e->getMessage());
        }
    }

    public function update(UpdateNasabahRequest $request, $id): RedirectResponse
    {
        try {
            $nasabah = $this->nasabahService->updateNasabah($id, $request->validated());
            return redirect()->route('admin.nasabahs.index')->with('success', 'Nasabah updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update nasabah: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->nasabahService->deleteNasabah($id);
            return redirect()->route('admin.nasabahs.index')->with('success', 'Nasabah deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete nasabah: ' . $e->getMessage());
        }
    }

    public function restore($id): RedirectResponse
    {
        try {
            $nasabah = $this->nasabahService->restoreNasabah($id);
            return redirect()->route('admin.nasabahs.index')->with('success', 'Nasabah restored successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to restore nasabah: ' . $e->getMessage());
        }
    }
}