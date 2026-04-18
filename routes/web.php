<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Pending notice — auth required, no role restriction
Route::middleware('auth')->group(function () {
    Route::get('/pending', function () {
        return view('pending');
    })->name('pending.notice');
});

// Authenticated routes — active, non-pending users
Route::middleware(['auth', 'verified', 'role'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/join', [EventParticipantController::class, 'store'])->name('events.join');
    Route::post('/events/{event}/proof', [EventParticipantController::class, 'submitProof'])->name('events.proof');
});

// Admin + Moderator — event CRUD + user search API
// Registered BEFORE the general {event} wildcard routes above to avoid "create" matching {event}
Route::middleware(['auth', 'verified', 'role:admin,moderator'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/api/users/search', function (Request $request) {
        return User::where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%')
              ->orWhere('email', 'like', '%' . $request->q . '%');
        })
        ->whereNotIn('role', ['pending'])
        ->where('status', 'active')
        ->select('id', 'name', 'email', 'committee')
        ->limit(10)
        ->get();
    })->name('api.users.search');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::delete('/users/{user}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    });

// Officer+ (placeholder for validation feature)
Route::middleware(['auth', 'verified', 'role:admin,moderator,officer'])
    ->prefix('validation')
    ->name('validation.')
    ->group(function () {
        // Future: participation validation
    });

require __DIR__ . '/auth.php';
