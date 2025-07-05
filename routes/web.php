<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\TransportsController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// ROUTE UNTUK LANDING PAGE & HALAMAN USER
// =========================================================================

// INI BAGIAN YANG DIPERBAIKI:
// Sekarang route '/' akan memanggil LandingPageController
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Auth::routes();
Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');


// =========================================================================
// SEMUA ROUTE ADMIN DISATUKAN DI SINI
// =========================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    // Resource routes
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('banks', BankController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('transports', TransportsController::class);
    
    // Route dashboard admin (jika ada)
    // Jika dashboard admin sama dengan dashboard user, route ini bisa dihapus dari sini
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});