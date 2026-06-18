<?php

use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/table-page', [HomeController::class, 'getTablePage'])->name('table.page');
Route::get('/pcl-table-page', [MonitoringController::class, 'getPclTablePage'])->name('pcl.table.page');
Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
Route::get('/leaderboard-pcl-page', [MonitoringController::class, 'getLeaderboardPage'])->name('leaderboard.pcl.page');
Route::get('/pml-leaderboard-page', [MonitoringController::class, 'getPmlLeaderboardPage'])->name('pml.leaderboard.page');
Route::get('/pcl-export', [MonitoringController::class, 'exportPclExcel'])->name('pcl.export');
Route::get('/pml-export', [MonitoringController::class, 'exportPmlExcel'])->name('pml.export');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/import', [ImportController::class, 'index'])->name('import');
    Route::post('/import/kecamatan', [ImportController::class, 'importKecamatanMapping'])->name('import.kecamatan');
    Route::post('/import/officer', [ImportController::class, 'importOfficerMapping'])->name('import.officer');
    Route::post('/import/monitoring', [ImportController::class, 'importMonitoringData'])->name('import.monitoring');
    Route::post('/import/clear', [ImportController::class, 'clearData'])->name('import.clear');
    Route::post('/import/clean-latest', [ImportController::class, 'cleanLatestImport'])->name('import.clean-latest');
    Route::post('/import/clear-leaderboard', [ImportController::class, 'clearLeaderboardData'])->name('import.clear-leaderboard');
});
