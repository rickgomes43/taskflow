<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store')
        ->middleware('throttle:5,1'); // Max 5 attempts per minute
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::get('/users', function () {
        return 'Lista de usuários (Admin apenas)';
    })->name('admin.users');
});

// Collaborator and Admin routes
Route::middleware(['auth', 'role:admin,colaborador'])->prefix('colaborador')->group(function () {
    Route::get('/projetos', function () {
        return 'Projetos (Admin e Colaborador)';
    })->name('collaborator.projects');
});

// Client area routes
Route::middleware(['auth', 'role:cliente'])->prefix('cliente')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard do Cliente';
    })->name('client.dashboard');
});
