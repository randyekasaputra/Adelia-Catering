<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PackageController;

Route::get('/', [FrontController::class, 'home']);
Route::get('/menu', [FrontController::class, 'menu']);
Route::get('/menu/{id}', [FrontController::class, 'menuDetail']);
Route::get('/checkout', [FrontController::class, 'checkout']);
Route::post('/checkout/process', [FrontController::class, 'processCheckout']);
Route::get('/tracking', [FrontController::class, 'tracking'])->name('tracking');

// Paket Menu Katering & Smart Optimizer
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{slug}', [PackageController::class, 'detail']);
Route::post('/api/packages/optimize', [PackageController::class, 'optimize']);

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminController::class, 'login']);

Route::middleware('auth')->prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/visitors', [AdminController::class, 'visitors']);
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

    // Quick Budget Recommender (Asisten Penjual)
    Route::get('/recommender', [AdminController::class, 'budgetRecommender']);
    Route::post('/recommender/calculate', [AdminController::class, 'calculateBudgetRecommendation']);

    // Data Penjualan Historis 3 Tahun & Import CSV
    Route::get('/historical-sales', [AdminController::class, 'historicalSales']);
    Route::get('/historical-sales/template', [AdminController::class, 'downloadSalesTemplate']);
    Route::post('/historical-sales/import', [AdminController::class, 'importHistoricalSales']);
    Route::post('/historical-sales/recalculate', [AdminController::class, 'recalculateWeights']);
    Route::delete('/historical-sales/truncate', [AdminController::class, 'truncateHistoricalSales']);
});

// Public API: Calendar availability data
Route::get('/api/calendar', [AdminController::class, 'calendarData']);

// Public API: Chatbot
Route::post('/api/chatbot', [\App\Http\Controllers\ChatbotController::class, 'chat']);

// Midtrans Webhook Notification Handler (Support both web & api paths)
Route::post('/payment/notification', [FrontController::class, 'paymentNotification']);

