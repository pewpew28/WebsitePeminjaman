<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Requests\Setting\ShowByKeySettingRequest;
use App\Http\Requests\Setting\UpdateOrCreateSettingRequest;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): View
    {
        try {
            $settings = $this->settingService->getAllSettings();
            return view('admin.settings.index', compact('settings'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch settings: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.settings.create');
    }

    public function show($id): View
    {
        try {
            $setting = $this->settingService->getSettingById($id);
            return view('admin.settings.show', compact('setting'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch setting: ' . $e->getMessage());
        }
    }

    public function showByKey(ShowByKeySettingRequest $request): View
    {
        try {
            $setting = $this->settingService->getSettingByKey($request->validated()['key']);
            if (!$setting) {
                return back()->with('error', 'Setting not found');
            }
            return view('admin.settings.show', compact('setting'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch setting: ' . $e->getMessage());
        }
    }

    public function store(StoreSettingRequest $request): RedirectResponse
    {
        try {
            $setting = $this->settingService->createSetting($request->validated());
            return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create setting: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        try {
            $setting = $this->settingService->getSettingById($id);
            return view('admin.settings.edit', compact('setting'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch setting: ' . $e->getMessage());
        }
    }

    public function update(UpdateSettingRequest $request, $id): RedirectResponse
    {
        try {
            $setting = $this->settingService->updateSetting($id, $request->validated());
            return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update setting: ' . $e->getMessage())->withInput();
        }
    }

    public function updateOrCreateByKey(UpdateOrCreateSettingRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $setting = $this->settingService->updateOrCreateSettingByKey($data['key'], $data['value']);
            return redirect()->route('admin.settings.index')->with('success', 'Setting updated or created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update or create setting: ' . $e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->settingService->deleteSetting($id);
            return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete setting: ' . $e->getMessage());
        }
    }
}