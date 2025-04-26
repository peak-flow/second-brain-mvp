<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TreeController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TopicController;
use App\Livewire\TreeManager;

// Livewire Tree Manager UI
Route::middleware('auth')->get('tree-manager', TreeManager::class)
    ->name('tree.manager');
// Livewire Task Planner UI
Route::middleware('auth')->get('task-planner', App\Livewire\TaskPlanner::class)
    ->name('task.planner');

// Livewire Agent Creator UI
Route::middleware('auth')->get('agent-creator', App\Livewire\AgentCreator::class)
    ->name('agent.creator');
// API routes
Route::prefix('api')
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
    ->group(function () {
        Route::get('projects', [ProjectController::class, 'index']);
        Route::post('projects', [ProjectController::class, 'store']);

        Route::get('trees', [TreeController::class, 'index']);
        Route::post('trees', [TreeController::class, 'store']);
        Route::put('trees/{id}/move', [TreeController::class, 'move']);
        Route::delete('trees/{id}', [TreeController::class, 'destroy']);
        Route::get('trees/{id}/subtree', [TreeController::class, 'subtree']);

        Route::get('tasks', [TaskController::class, 'index']);
        Route::post('tasks', [TaskController::class, 'store']);
        Route::get('topics', [TopicController::class, 'index']);
        Route::post('topics', [TopicController::class, 'store']);
    });
