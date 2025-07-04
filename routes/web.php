<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NasabahController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\InstallmentController;
use App\Http\Controllers\Admin\CollectorTaskController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    switch (auth()->user()->role) {
        case 'admin':
            return redirect()->intended(route('admin.dashboard'));
        case 'finance':
            return redirect()->intended(route('finance.dashboard'));
        case 'collector':
            return redirect()->intended(route('collector.dashboard'));
        case 'nasabah':
            return redirect()->intended(route('nasabah.dashboard'));
        default:
            abort(403, 'Unauthorized');
    }
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
        // Payment Route
        Route::get('payment', [PaymentController::class, 'form'])->name('payment.form');
        Route::post('payment/store', [PaymentController::class, 'store'])->name('payment.store');
        Route::get('/payment/history/{nasabah}', [PaymentController::class, 'history'])->name('admin.payment.history');
        Route::get('/payment/receipt/{installment}', [PaymentController::class, 'receipt'])->name('admin.payment.receipt');
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

require __DIR__ . '/auth.php';
