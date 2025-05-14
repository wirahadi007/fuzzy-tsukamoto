<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/debug-middleware', function () {
    dd(app(\Illuminate\Routing\Router::class)->getMiddleware());
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes untuk Admin dan Manager
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/projects/fuzzy-analysis', [ProjectController::class, 'fuzzyAnalysis'])->name('projects.fuzzy-analysis');
        Route::resource('projects', ProjectController::class);
    });
});


// Route untuk fuzzy analysis (bisa diakses manager)
// Route::get('/projects/fuzzy-analysis', [ProjectController::class, 'fuzzyAnalysis'])
//     ->name('projects.fuzzy-analysis');

// Route untuk accounting
Route::middleware(['role:accounting'])->group(function () {
    Route::get('/project-monitoring', [ProjectController::class, 'monitoring'])
        ->name('projects.monitoring');
});

// Profile Routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

require __DIR__.'/auth.php';
