<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $projects = auth()->user()->hasRole('admin') ? \App\Models\Project::with('division')->get() : null;
        return view('dashboard', compact('projects'));
    })->name('dashboard');

    // Admin Routes
    Route::middleware([\App\Http\Middleware\CheckRole::class.':admin'])->group(function () {
        Route::resource('projects', ProjectController::class);
    });

    // Accounting Routes
    Route::middleware([\App\Http\Middleware\CheckRole::class.':accounting'])->group(function () {
        Route::get('/project-monitoring', [ProjectController::class, 'monitoring'])->name('projects.monitoring');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
