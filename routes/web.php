<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ─── Guest ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// ─── Authenticated ────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile (shared)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',      [ProfileController::class, 'update'])->name('profile.update');

    // Messages (shared)
    Route::get('/messages',              [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{contact}',    [MessageController::class, 'thread'])->name('messages.thread');
    Route::post('/messages/{contact}',   [MessageController::class, 'send'])->name('messages.send');

    // ─── Patient ──────────────────────────────────────────────
    Route::middleware('role:patient')->group(function () {
        Route::get('/',                                       [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/{doctor}',                       [DoctorController::class, 'show'])->name('doctors.show');
        Route::get('/doctors/{doctor}/book',                  [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/doctors/{doctor}/book',                 [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/my-appointments',                        [AppointmentController::class, 'patientIndex'])->name('patient.appointments');
        Route::get('/my-appointments/{appointment}',          [AppointmentController::class, 'show'])->name('patient.appointments.show');
        Route::patch('/my-appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::post('/appointments/{appointment}/review',     [ReviewController::class, 'store'])->name('reviews.store');
    });

    // ─── Doctor ───────────────────────────────────────────────
    Route::middleware('role:doctor')->group(function () {
        Route::get('/dashboard',                              [DoctorController::class, 'dashboard'])->name('doctor.dashboard');
        Route::get('/appointments',                           [AppointmentController::class, 'doctorIndex'])->name('doctor.appointments');
        Route::get('/appointments/{appointment}',             [AppointmentController::class, 'show'])->name('doctor.appointments.show');
        Route::patch('/appointments/{appointment}/accept',    [AppointmentController::class, 'accept'])->name('appointments.accept');
        Route::patch('/appointments/{appointment}/refuse',    [AppointmentController::class, 'refuse'])->name('appointments.refuse');
        Route::patch('/appointments/{appointment}/complete',  [AppointmentController::class, 'complete'])->name('appointments.complete');
        Route::patch('/appointments/{appointment}/notes',     [AppointmentController::class, 'addNotes'])->name('appointments.notes');
    });
});
