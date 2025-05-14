<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\CheckIfStaff;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', CheckIfStaff::class])->group(function () {
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
});
Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');


Route::get('/cars', [CarController::class, 'index'])->name('cars.index');

Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Customer Dashboard with Role Middleware
Route::get('/customer/dashboard', function () {
    return view('dashboard.customer');
})->middleware(['auth', 'role:customer']);  // Correct role usage

// Register Routes
Route::get('/register/customer', [RegisterController::class, 'showCustomerRegisterForm'])->name('register.customer');
Route::post('/register/customer', [RegisterController::class, 'registerCustomer']);

Route::get('/register/staff', [RegisterController::class, 'showStaffRegisterForm'])->name('register.staff');
Route::post('/register/staff', [RegisterController::class, 'registerStaff']);

// Staff Dashboard with Role Middleware
Route::get('/staff/dashboard', function () {
    return view('dashboard.staff');
})->middleware(['auth', 'role:staff']);  // Correct role usage

// Show booking form for a specific car
Route::get('/bookings/create/{car}', [App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings/store', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');

