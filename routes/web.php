<?php

use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SupplierController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('login');
})->name('login.form');


Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(([CheckLogin::class]))->group(function () {


    Route::get('/dashboard', [LoginController::class, 'index'])
    ->middleware(['check.login'])
    ->name('dashboard.index');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware(['check.login'])
        ->name('logout');


    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.update');
        
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])
        ->name('index');
    Route::post('/store', [SupplierController::class, 'store'])
        ->name('store');
    Route::post('/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])
        ->name('toggle-status');
    Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [SupplierController::class, 'update'])->name('update');
});

});


