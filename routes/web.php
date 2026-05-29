<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReceptionController;

Route::get('/', function () {
    $packages = \App\Models\MassagePackage::all();
    $staffs = \App\Models\Staff::where('is_active', true)->get();
    return view('welcome', compact('packages', 'staffs'));
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Users (Receptionists)
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
        Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');

        // Staff
        Route::get('/staff', [AdminController::class, 'staffIndex'])->name('staff.index');
        Route::post('/staff', [AdminController::class, 'staffStore'])->name('staff.store');
        Route::put('/staff/{staff}', [AdminController::class, 'staffUpdate'])->name('staff.update');
        Route::delete('/staff/{staff}', [AdminController::class, 'staffDestroy'])->name('staff.destroy');

        // Packages
        Route::get('/packages', [AdminController::class, 'packagesIndex'])->name('packages.index');
        Route::post('/packages', [AdminController::class, 'packagesStore'])->name('packages.store');
        Route::put('/packages/{package}', [AdminController::class, 'packagesUpdate'])->name('packages.update');
        Route::delete('/packages/{package}', [AdminController::class, 'packagesDestroy'])->name('packages.destroy');
        
        // Tracking & Cari
        Route::get('/tracking', [AdminController::class, 'tracking'])->name('tracking');
        Route::get('/cari', [AdminController::class, 'cari'])->name('cari');
        Route::post('/cari/expense', [AdminController::class, 'expenseStore'])->name('cari.expense');
    });

    // Reception Routes
    Route::middleware('role:reception')->prefix('reception')->name('reception.')->group(function () {
        Route::get('/dashboard', [ReceptionController::class, 'dashboard'])->name('dashboard');
        
        // Records
        Route::get('/records', [ReceptionController::class, 'recordsIndex'])->name('records.index');
        Route::post('/records', [ReceptionController::class, 'recordsStore'])->name('records.store');
        Route::put('/records/{record}', [ReceptionController::class, 'recordsUpdate'])->name('records.update');
        Route::delete('/records/{record}', [ReceptionController::class, 'recordsDestroy'])->name('records.destroy');
        
        // Expenses
        Route::get('/expenses', [ReceptionController::class, 'expensesIndex'])->name('expenses.index');
        Route::post('/expenses', [ReceptionController::class, 'expensesStore'])->name('expenses.store');
    });
});
