<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;

Route::get('/', [FrontController::class, 'home']);
Route::get('/menu', [FrontController::class, 'menu']);
Route::get('/menu/{id}', [FrontController::class, 'menuDetail']);
Route::get('/checkout', [FrontController::class, 'checkout']);
Route::post('/checkout/process', [FrontController::class, 'processCheckout']);

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('auth')->prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::post('/logout', [AdminController::class, 'logout']);
    
    Route::get('/menus', [AdminController::class, 'menus']);
    Route::get('/menus/create', [AdminController::class, 'createMenu']);
    Route::post('/menus', [AdminController::class, 'storeMenu']);
    Route::get('/menus/{id}/edit', [AdminController::class, 'editMenu']);
    Route::post('/menus/{id}', [AdminController::class, 'updateMenu']);
    Route::delete('/menus/{id}', [AdminController::class, 'destroyMenu']);

    Route::get('/orders', [AdminController::class, 'orders']);
    Route::get('/orders/{id}/print', [AdminController::class, 'printOrder']);
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);

    // Booking Calendar Management
    Route::get('/booking', [AdminController::class, 'bookingCalendar']);
    Route::post('/booking/settings', [AdminController::class, 'updateBookingSettings']);
    Route::post('/booking/block', [AdminController::class, 'blockDate']);
    Route::delete('/booking/unblock/{id}', [AdminController::class, 'unblockDate']);
});

// Public API: Calendar availability data
Route::get('/api/calendar', [AdminController::class, 'calendarData']);

// Public API: Chatbot
Route::post('/api/chatbot', [\App\Http\Controllers\ChatbotController::class, 'chat']);

