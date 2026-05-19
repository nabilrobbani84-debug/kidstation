<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.store');
});

// Google callback must be OUTSIDE the 'guest' middleware so that session state
// mismatches on serverless (Vercel) don't cause a redirect loop.
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::post('logout', [AuthController::class, 'logout'])->middleware('admin.auth')->name('logout');

Route::middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('sales', SaleController::class)->only(['index', 'store', 'destroy']);
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/sales', [ReportController::class, 'exportSales'])->name('reports.export.sales');
    Route::get('reports/export/expenses', [ReportController::class, 'exportExpenses'])->name('reports.export.expenses');
    Route::post('notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
