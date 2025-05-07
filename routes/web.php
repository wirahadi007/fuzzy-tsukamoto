<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    // Admin Routes
    // Route::group(['middleware' => ['auth', 'role:admin']], function () {
        // Place this before the resource route
        Route::get('/projects/fuzzy-analysis', [ProjectController::class, 'fuzzyAnalysis'])->name('projects.fuzzy-analysis');
        
        Route::resource('projects', ProjectController::class);
    // });

    // Accounting Routes
    Route::group(['middleware' => ['auth', 'role:accounting']], function () {
        Route::get('/project-monitoring', [ProjectController::class, 'monitoring'])->name('projects.monitoring');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
