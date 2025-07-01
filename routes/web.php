<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// ==================
// Authenticated Routes
// ==================

Route::middleware(['auth'])->group(function () {

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'userIndex'])->name('index');
            Route::get('/create', [AdminController::class, 'userCreate'])->name('create');
            Route::get('/store', [AdminController::class, 'userStore'])->name('store');
            Route::get('/{userId}/show', [AdminController::class, 'userShow'])->name('show');
            Route::get('/{userId}/edit', [AdminController::class, 'userEdit'])->name('edit');
            Route::get('/{userId}/update', [AdminController::class, 'userUpdate'])->name('update');
            Route::get('/{userId}/destroy', [AdminController::class, 'userDestroy'])->name('destroy');
        });
    });

    // Finance Routes
    Route::prefix('finance')->middleware('role:finance')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('finance.dashboard');
    });

    // Collector Routes
    Route::prefix('collector')->middleware('role:collector')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('collector.dashboard');
    });

    // Nasabah Routes
    Route::prefix('nasabah')->middleware('role:nasabah')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('nasabah.dashboard');
    });
});

require __DIR__.'/auth.php';
