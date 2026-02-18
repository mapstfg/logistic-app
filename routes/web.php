<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Medico\PrescriptionController;
use App\Http\Controllers\Farmacia\DeliveryController;
use App\Http\Controllers\Bodega\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('medicines', \App\Http\Controllers\MedicineController::class);
    Route::resource('supplies', \App\Http\Controllers\SupplyController::class);
    Route::resource('patients', \App\Http\Controllers\PatientController::class);

    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::middleware(['role:MEDICO'])->prefix('medico')->name('medico.')->group(function () {
        Route::resource('prescriptions', PrescriptionController::class);
    });

    Route::middleware(['role:FARMACIA'])->prefix('farmacia')->name('farmacia.')->group(function () {
        Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
        Route::get('/deliveries/{prescription}', [DeliveryController::class, 'show'])->name('deliveries.show');
        Route::post('/deliveries/{prescription}', [DeliveryController::class, 'store'])->name('deliveries.store');
    });

    Route::middleware(['role:BODEGA'])->prefix('bodega')->name('bodega.')->group(function () {
        Route::resource('transfers', \App\Http\Controllers\Bodega\TransferController::class);
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('/stock/audit', [StockController::class, 'audit'])->name('stock.audit');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// require __DIR__.'/auth.php'; // Removed
