<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/table-page', [HomeController::class, 'getTablePage'])->name('table.page');
