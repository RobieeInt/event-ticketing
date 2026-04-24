<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrganizerApplicationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-email', function () {
    Mail::raw('Halo ini email test', function ($m) {
        $m->to('test@example.com')->subject('Test Mailpit');
    });
});

Route::middleware(['auth','otp'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/apply-organizer', [OrganizerApplicationController::class, 'create'])
        ->name('organizer.apply');

    Route::post('/apply-organizer', [OrganizerApplicationController::class, 'store'])
        ->name('organizer.apply.store');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
