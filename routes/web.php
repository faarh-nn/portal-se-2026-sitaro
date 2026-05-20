<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/table-page', [HomeController::class, 'getTablePage'])->name('table.page');
Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
