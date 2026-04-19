<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TermController;
use App\Http\Controllers\Admin\TermOfficerController;
use App\Http\Controllers\Admin\CommitteeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\UserPositionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ValidationController;
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

// Admin + Moderator — event CRUD + user search API
// Must be registered BEFORE the general role group to prevent /events/create matching {event}
Route::middleware(['auth', 'verified', 'role:admin,moderator'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/certificates/release', [CertificateController::class, 'release'])->name('events.certificates.release');

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
    Route::get('/events/{event}/certificate', [CertificateController::class, 'download'])->name('events.certificate');

    Route::get('/administration', [TermController::class, 'public'])->name('administration.index');
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

        Route::resource('terms', TermController::class)->except(['show']);
        Route::get('/terms/{term}', [TermController::class, 'show'])->name('terms.show');
        Route::post('/terms/{term}/activate', [TermController::class, 'activate'])->name('terms.activate');
        Route::post('/terms/{term}/officers', [TermOfficerController::class, 'store'])->name('terms.officers.store');
        Route::delete('/terms/{term}/officers/{officer}', [TermOfficerController::class, 'destroy'])->name('terms.officers.destroy');

        // Committee & position management (admin-only CRUD)
        Route::resource('committees', CommitteeController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('positions', PositionController::class)->only(['index', 'store', 'update', 'destroy']);

        // Assignment approval queue (admin-only view + approve/reject)
        Route::get('/assignments', [UserPositionController::class, 'index'])->name('assignments.index');
        Route::post('/assignments/{assignment}/approve', [UserPositionController::class, 'approve'])->name('assignments.approve');
        Route::post('/assignments/{assignment}/reject', [UserPositionController::class, 'reject'])->name('assignments.reject');
        Route::delete('/assignments/{assignment}', [UserPositionController::class, 'destroy'])->name('assignments.destroy');
    });

// Admin + Moderator — assignment creation
Route::middleware(['auth', 'verified', 'role:admin,moderator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/assignments/create', [UserPositionController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [UserPositionController::class, 'store'])->name('assignments.store');
    });

// Officer+ — participation validation queue
Route::middleware(['auth', 'verified', 'role:admin,moderator,officer'])
    ->prefix('validation')
    ->name('validation.')
    ->group(function () {
        Route::get('/', [ValidationController::class, 'index'])->name('index');
        Route::post('/{eventId}/{userId}/approve', [ValidationController::class, 'approve'])->name('approve');
        Route::post('/{eventId}/{userId}/reject', [ValidationController::class, 'reject'])->name('reject');
    });

require __DIR__ . '/auth.php';
