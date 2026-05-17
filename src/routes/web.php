<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizerApplicationController;
use App\Http\Controllers\Admin\OrganizerApplicationController as AdminOrganizerApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

// Halaman khusus untuk akun yang disuspend
Route::get('/suspended', function () {
    return view('suspended');
})->name('suspended');

Route::middleware(['auth', 'otp', 'suspended'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/apply-organizer', [OrganizerApplicationController::class, 'create'])
        ->name('organizer.apply');
    Route::post('/apply-organizer', [OrganizerApplicationController::class, 'store'])
        ->name('organizer.apply.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/verify', [OrderController::class, 'verify'])->name('orders.verify');
});

// Public event pages (no auth required)
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

// Organizer routes
Route::middleware(['auth', 'otp', 'suspended', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/', [OrganizerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', EventController::class);
    Route::get('events/{event}/checkin', [EventController::class, 'checkin'])->name('events.checkin');
    Route::post('events/{event}/scan', [EventController::class, 'scan'])->name('events.scan');
});

// Admin routes
Route::middleware(['auth', 'otp', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/organizer', [AdminOrganizerApplicationController::class, 'index'])
        ->name('organizer.index');

    Route::post('/organizer/{application}/approve', [AdminOrganizerApplicationController::class, 'approve'])
        ->name('organizer.approve');

    Route::post('/organizer/{application}/reject', [AdminOrganizerApplicationController::class, 'reject'])
        ->name('organizer.reject');

    Route::post('/organizer/{application}/suspend', [AdminOrganizerApplicationController::class, 'suspend'])
        ->name('organizer.suspend');

    Route::post('/organizer/{application}/unsuspend', [AdminOrganizerApplicationController::class, 'unsuspend'])
        ->name('organizer.unsuspend');
});

// Midtrans webhook — no auth, no CSRF
Route::post('/midtrans/notification', [OrderController::class, 'notification'])
    ->name('midtrans.notification')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
