<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Public booking form
Route::get('/', [BookingController::class, 'index'])->name('booking.form');
Route::get('/api/available-slots', [BookingController::class, 'getAvailableSlots'])->name('booking.slots');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');

// Admin routes
Route::get('/admin', [BookingController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/bookings', [BookingController::class, 'adminBookings'])->name('admin.bookings');
