<?php

// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\RegisterController;
// use App\Http\Controllers\LoginController;
// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// // Route::get('/', function () {
// //     return view('welcome');
// // });


// Route::get('/', [HomeController::class, 'index'])->name('home');
// // ورود
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// // ثبت‌نام
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// // خروج
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
//     Route::get('/dashboard', 'AdminController@dashboard');
//     // سایر مسیرهای ادمین
// });

// Route::post('/reservations/{gymSession}', [ReservationController::class, 'store'])
//     ->name('reservations.store')
//     ->middleware('auth');



// require __DIR__.'/auth.php';

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
use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// صفحه اصلی
Route::get('/', [HomeController::class, 'index'])->name('home');


// احراز هویت
Route::middleware('guest')->group(function () {

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/sessions', [GymSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{gymSession}', [GymSessionController::class, 'show'])->name('sessions.show');
});

// خروج
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// داشبورد کاربری
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/join-requests/{id}', [DashboardController::class, 'handleJoinRequest'])
        ->name('join-requests.handle');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // رزرو سانس
    Route::post('/reservations/{gymSession}', [ReservationController::class, 'store'])
        ->name('reservations.store');
        
    Route::get('/sessions', [GymSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{gymSession}', [GymSessionController::class, 'show'])->name('sessions.show');
        
   });

// مسیرهای ادمین
Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {

    Route::post('/announcements', [AdminController::class, 'addAnnouncement'])->name('admin.announcements.add');
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('admin.announcements.delete');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/reservations/{id}/approve', [AdminController::class, 'approveReservation'])->name('admin.reservations.approve');
    Route::post('/reservations/{id}/reject', [AdminController::class, 'rejectReservation'])->name('admin.reservations.reject');
    Route::post('/sessions', [AdminController::class, 'addSession'])->name('admin.sessions.add');
    Route::delete('/sessions/{id}', [AdminController::class, 'deleteSession'])->name('admin.sessions.delete');
    Route::post('/announcements', [AdminController::class, 'addAnnouncement'])->name('admin.announcements.add');
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('admin.announcements.delete');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/join-requests/{reservation}/create', [JoinRequestController::class, 'create'])
         ->name('join-requests.create');
         
    // تغییر از {reservation} به {reservationId} برای تطابق با کنترلر
    Route::post('/join-requests/{reservationId}', [JoinRequestController::class, 'store'])
         ->name('join-requests.store');
    
    // رزرو سانس
    Route::get('/sessions/{gymSession}/reserve', [ReservationController::class, 'create'])
        ->name('reservations.create');
    Route::post('/sessions/{gymSession}/reserve', [ReservationController::class, 'store'])
        ->name('reservations.store');

    // مدیریت رزروها
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

require __DIR__.'/auth.php';
