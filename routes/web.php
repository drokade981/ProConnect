<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProviderController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Panel Routes with 'admin' middleware protection
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Providers & KYC
    Route::get('/providers', [AdminProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers/{id}', [AdminProviderController::class, 'show'])->name('providers.show');
    Route::post('/providers/doc/{id}/verify', [AdminProviderController::class, 'verifyDocument'])->name('providers.verify-doc');
    Route::post('/providers/{id}/status', [AdminProviderController::class, 'updateStatus'])->name('providers.update-status');

    // Services Catalog & Categories
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::post('/services/category', [AdminServiceController::class, 'storeCategory'])->name('services.category.store');
    Route::post('/services/service', [AdminServiceController::class, 'storeService'])->name('services.service.store');
    Route::post('/services/{id}/toggle', [AdminServiceController::class, 'toggleService'])->name('services.toggle');

    // Bookings & Dispatch
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Support Tickets & Disputes
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets/{id}', [AdminTicketController::class, 'update'])->name('tickets.update');
});

require __DIR__.'/auth.php';


