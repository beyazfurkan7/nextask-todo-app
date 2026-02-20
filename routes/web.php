<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
    
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroyProject'])->name('projects.destroy');

    Route::post('/projects/{project}/tasks', [ProjectController::class, 'storeTask'])->name('tasks.store');
    Route::put('/tasks/{task}', [ProjectController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{task}', [ProjectController::class, 'destroyTask'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/toggle', [ProjectController::class, 'toggleTask'])->name('tasks.toggle');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




