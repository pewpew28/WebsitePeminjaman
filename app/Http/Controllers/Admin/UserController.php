<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): View
    {
        try {
            $filters = $request->query();
            $users = $this->userService->getAllUsers($filters);
            return view('admin.users.index', compact('users'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch users: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function show($id): View
    {
        try {
            $user = $this->userService->getUserById($id);
            return view('admin.users.show', compact('user'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch user: ' . $e->getMessage());
        }
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id): View
    {
        try {
            $user = $this->userService->getUserById($id);
            return view('admin.users.edit', compact('user'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch user: ' . $e->getMessage());
        }
    }

    public function update(UpdateUserRequest $request, $id): RedirectResponse
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $this->userService->deleteUser($id);
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function restore($id): RedirectResponse
    {
        try {
            $user = $this->userService->restoreUser($id);
            return redirect()->route('admin.users.index')->with('success', 'User restored successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to restore user: ' . $e->getMessage());
        }
    }

    public function uploadProfilePicture(Request $request, $id): RedirectResponse
    {
        try {
            $request->validate(['file' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
            $media = $this->userService->uploadProfilePicture($id, $request->file('file'));
            return redirect()->route('admin.users.show', $id)->with('success', 'Profile picture uploaded successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to upload profile picture: ' . $e->getMessage());
        }
    }

    public function updateLastSeen($id): RedirectResponse
    {
        try {
            $user = $this->userService->updateLastSeen($id);
            return redirect()->route('admin.users.show', $id)->with('success', 'Last seen updated successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update last seen: ' . $e->getMessage());
        }
    }
}