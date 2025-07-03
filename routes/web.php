<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NasabahController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\InstallmentController;
use App\Http\Controllers\Admin\CollectorTaskController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdminController;

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

        // User Routes
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::post('users/{id}/upload-profile-picture', [UserController::class, 'uploadProfilePicture'])->name('users.upload-profile-picture');
        Route::post('users/{id}/last-seen', [UserController::class, 'updateLastSeen'])->name('users.last-seen');

        // Nasabah Routes
        Route::get('nasabahs', [NasabahController::class, 'index'])->name('nasabahs.index');
        Route::get('nasabahs/create', [NasabahController::class, 'create'])->name('nasabahs.create');
        Route::post('nasabahs', [NasabahController::class, 'store'])->name('nasabahs.store');
        Route::get('nasabahs/{id}', [NasabahController::class, 'show'])->name('nasabahs.show');
        Route::get('nasabahs/{id}/edit', [NasabahController::class, 'edit'])->name('nasabahs.edit');
        Route::put('nasabahs/{id}', [NasabahController::class, 'update'])->name('nasabahs.update');
        Route::delete('nasabahs/{id}', [NasabahController::class, 'destroy'])->name('nasabahs.destroy');
        Route::post('nasabahs/{id}/restore', [NasabahController::class, 'restore'])->name('nasabahs.restore');

        // Loan Routes
        Route::get('loans', [LoanController::class, 'index'])->name('loans.index');
        Route::get('loans/create', [LoanController::class, 'create'])->name('loans.create');
        Route::post('loans', [LoanController::class, 'store'])->name('loans.store');
        Route::get('loans/{id}', [LoanController::class, 'show'])->name('loans.show');
        Route::get('loans/{id}/edit', [LoanController::class, 'edit'])->name('loans.edit');
        Route::put('loans/{id}', [LoanController::class, 'update'])->name('loans.update');
        Route::delete('loans/{id}', [LoanController::class, 'destroy'])->name('loans.destroy');
        Route::post('loans/{id}/restore', [LoanController::class, 'restore'])->name('loans.restore');
        Route::post('loans/{id}/approve', [LoanController::class, 'approve'])->name('loans.approve');

        // Installment Routes
        Route::get('installments', [InstallmentController::class, 'index'])->name('installments.index');
        Route::get('installments/create', [InstallmentController::class, 'create'])->name('installments.create');
        Route::post('installments', [InstallmentController::class, 'store'])->name('installments.store');
        Route::get('installments/{id}', [InstallmentController::class, 'show'])->name('installments.show');
        Route::get('installments/{id}/edit', [InstallmentController::class, 'edit'])->name('installments.edit');
        Route::put('installments/{id}', [InstallmentController::class, 'update'])->name('installments.update');
        Route::delete('installments/{id}', [InstallmentController::class, 'destroy'])->name('installments.destroy');
        Route::post('installments/{id}/restore', [InstallmentController::class, 'restore'])->name('installments.restore');
        Route::post('installments/{id}/record-payment', [InstallmentController::class, 'recordPayment'])->name('installments.record-payment');
        Route::post('installments/{id}/upload-payment-proof', [InstallmentController::class, 'uploadPaymentProof'])->name('installments.upload-payment-proof');

        // Collector Task Routes
        Route::get('collector-tasks', [CollectorTaskController::class, 'index'])->name('collector-tasks.index');
        Route::get('collector-tasks/create', [CollectorTaskController::class, 'create'])->name('collector-tasks.create');
        Route::post('collector-tasks', [CollectorTaskController::class, 'store'])->name('collector-tasks.store');
        Route::get('collector-tasks/{id}', [CollectorTaskController::class, 'show'])->name('collector-tasks.show');
        Route::get('collector-tasks/{id}/edit', [CollectorTaskController::class, 'edit'])->name('collector-tasks.edit');
        Route::put('collector-tasks/{id}', [CollectorTaskController::class, 'update'])->name('collector-tasks.update');
        Route::delete('collector-tasks/{id}', [CollectorTaskController::class, 'destroy'])->name('collector-tasks.destroy');
        Route::post('collector-tasks/{id}/restore', [CollectorTaskController::class, 'restore'])->name('collector-tasks.restore');
        Route::post('collector-tasks/{id}/record-collection', [CollectorTaskController::class, 'recordCollection'])->name('collector-tasks.record-collection');

        // Setting Routes
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('settings/create', [SettingController::class, 'create'])->name('settings.create');
        Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
        Route::get('settings/{id}', [SettingController::class, 'show'])->name('settings.show');
        Route::post('settings/by-key', [SettingController::class, 'showByKey'])->name('settings.show-by-key');
        Route::get('settings/{id}/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings/{id}', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/update-or-create', [SettingController::class, 'updateOrCreateByKey'])->name('settings.update-or-create');
        Route::delete('settings/{id}', [SettingController::class, 'destroy'])->name('settings.destroy');
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