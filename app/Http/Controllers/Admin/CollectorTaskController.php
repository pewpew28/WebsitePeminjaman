<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectorTask\StoreCollectorTaskRequest;
use App\Http\Requests\CollectorTask\UpdateCollectorTaskRequest;
use App\Http\Requests\CollectorTask\RecordCollectionRequest;
use App\Services\CollectorTaskService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class CollectorTaskController extends Controller
{
    protected $collectorTaskService;

    public function __construct(CollectorTaskService $collectorTaskService)
    {
        $this->collectorTaskService = $collectorTaskService;
    }

    public function index(Request $request): View
    {
        try {
            $filters = $request->query();
            $collectorTasks = $this->collectorTaskService->getAllCollectorTasks($filters);
            return view('admin.collector-tasks.index', compact('collectorTasks'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch collector tasks: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.collector-tasks.create');
    }

    public function show($id): View
    {
        try {
            $collectorTask = $this->collectorTaskService->getCollectorTaskById($id);
            return view('admin.collector-tasks.show', compact('collectorTask'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch collector task: ' . $e->getMessage());
        }
    }

    public function store(StoreCollectorTaskRequest $request): RedirectResponse
    {
        try {
            $collectorTask = $this->collectorTaskService->createCollectorTask($request->validated());
            return redirect()->route('admin.collector-tasks.index')->with('success', 'Collector task created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create collector task: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        try {
            $collectorTask = $this->collectorTaskService->getCollectorTaskById($id);
            return view('admin.collector-tasks.edit', compact('collectorTask'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch collector task: ' . $e->getMessage());
        }
    }

    public function update(UpdateCollectorTaskRequest $request, $id): RedirectResponse
    {
        try {
            $collectorTask = $this->collectorTaskService->updateCollectorTask($id, $request->validated());
            return redirect()->route('admin.collector-tasks.index')->with('success', 'Collector task updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update collector task: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->collectorTaskService->deleteCollectorTask($id);
            return redirect()->route('admin.collector-tasks.index')->with('success', 'Collector task deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete collector task: ' . $e->getMessage());
        }
    }

    public function restore($id): RedirectResponse
    {
        try {
            $collectorTask = $this->collectorTaskService->restoreCollectorTask($id);
            return redirect()->route('admin.collector-tasks.index')->with('success', 'Collector task restored successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to restore collector task: ' . $e->getMessage());
        }
    }

    public function recordCollection(RecordCollectionRequest $request, $id): RedirectResponse
    {
        try {
            $data = $request->validated();
            $collectorTask = $this->collectorTaskService->recordCollection($id, $data['amount_collected'], $data);
            return redirect()->route('admin.collector-tasks.show', $id)->with('success', 'Collection recorded successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to record collection: ' . $e->getMessage());
        }
    }
}