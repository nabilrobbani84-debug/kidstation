<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('products', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('sales', SaleController::class)->only(['index', 'store', 'destroy']);
Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy']);
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/export/sales', [ReportController::class, 'exportSales'])->name('reports.export.sales');
Route::get('reports/export/expenses', [ReportController::class, 'exportExpenses'])->name('reports.export.expenses');
Route::post('notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
