<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
})->name('home');

// Guest routes (Hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    // --- RUTE LOGIN & GOOGLE ---
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [GoogleController::class, 'login'])->name('login.post');
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // --- RUTE UNTUK RESET PASSWORD ---
    Route::get('forgot-password', function () {
        return view('auth.forgot-password'); // Pastikan view ini ada: resources/views/auth/forgot-password.blade.php
    })->name('password.request');

    // --- RUTE UNTUK REGISTRASI MULTI-LANGKAH ---
    Route::get('auth/complete-registration/step1', [GoogleController::class, 'showCompleteFormStep1'])->name('auth.complete.step1.form');
    Route::post('auth/complete-registration/step1', [GoogleController::class, 'storeRegistrationStep1'])->name('auth.complete.step1.store');

    Route::get('auth/complete-registration/step2', [GoogleController::class, 'showCompleteFormStep2'])->name('auth.complete.step2.form');
    Route::post('auth/complete-registration/step2', [GoogleController::class, 'storeRegistrationStep2'])->name('auth.complete.step2.store');

    Route::get('auth/complete-registration/step3', [GoogleController::class, 'showCompleteFormStep3'])->name('auth.complete.step3.form');
    Route::post('auth/complete-registration/step3', [GoogleController::class, 'finalizeRegistration'])->name('auth.complete.step3.store');
});

// Authenticated routes (Hanya bisa diakses jika sudah login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('layouts.dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

    // --- RUTE PROFIL ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // --- RUTE BARU UNTUK HALAMAN TRACKING ---
    Route::get('/tracking', function () {
        return view('layouts.tracking'); // Mengarahkan ke file tracking.blade.php yang baru dibuat
    })->name('tracking.start');
});