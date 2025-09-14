<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('home');

// Guest routes (only accessible when not logged in)
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [GoogleController::class, 'login']);

    // Google authentication routes
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Multi-step registration routes for Google users
    Route::get('/register-step1', [GoogleController::class, 'showCompleteFormStep1'])->name('auth.complete.step1.form');
    Route::post('/register-step1', [GoogleController::class, 'storeRegistrationStep1'])->name('auth.complete.step1.store');

    Route::get('/register-step2', [GoogleController::class, 'showCompleteFormStep2'])->name('auth.complete.step2.form');
    Route::post('/register-step2', [GoogleController::class, 'storeRegistrationStep2'])->name('auth.complete.step2.store');

    Route::get('/register-step3', [GoogleController::class, 'showCompleteFormStep3'])->name('auth.complete.step3.form');
    Route::post('/register-step3', [GoogleController::class, 'finalizeRegistration'])->name('auth.complete.step3.finalize');


    // Password reset routes
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

// Authenticated routes (only accessible after login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

    // Additional authenticated routes can be added here
});