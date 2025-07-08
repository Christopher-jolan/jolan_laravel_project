<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\GymSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/sessions', [GymSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{gymSession}', [GymSessionController::class, 'show'])->name('sessions.show');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/reservations/{gymSession}', [ReservationController::class, 'store'])
        ->name('reservations.store');
    Route::get('/sessions', [GymSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{gymSession}', [GymSessionController::class, 'show'])->name('sessions.show');
    Route::get('/join-requests/{reservation}/create', [JoinRequestController::class, 'create'])
        ->name('join-requests.create');
    Route::post('/join-requests/{reservation}', [JoinRequestController::class, 'store'])
        ->name('join-requests.store');
    Route::post('/join-requests/{id}/handle', [DashboardController::class, 'handleJoinRequest'])
        ->name('join-requests.handle');
   });

Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::post('/announcements', [AdminController::class, 'addAnnouncement'])->name('admin.announcements.add');
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('admin.announcements.delete');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/reservations/{id}/approve', [AdminController::class, 'approveReservation'])->name('admin.reservations.approve');
    Route::post('/reservations/{id}/reject', [AdminController::class, 'rejectReservation'])->name('admin.reservations.reject');
    Route::post('/sessions', [AdminController::class, 'addSession'])->name('admin.sessions.add');
    Route::delete('/sessions/{id}', [AdminController::class, 'deleteSession'])->name('admin.sessions.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sessions/{gymSession}/reserve', [ReservationController::class, 'create'])
        ->name('reservations.create');
    Route::post('/sessions/{gymSession}/reserve', [ReservationController::class, 'store'])
        ->name('reservations.store');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::post('/team-members', [TeamMemberController::class, 'store'])
        ->name('team-members.store');
    Route::delete('/team-members/{teamMember}', [TeamMemberController::class, 'destroy'])
        ->name('team-members.destroy');
    Route::get('/my-sessions', [GymSessionController::class, 'mySessions'])
        ->name('sessions.my');
});

require __DIR__.'/auth.php';