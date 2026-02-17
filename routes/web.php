<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPasswordController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('login');
})->name('login.form');


Route::post('/login', [LoginController::class, 'login'])->name('login');


Route::get('/dashboard', function () {
    return view('index');
})
    ->middleware(['check.login'])
    ->name('dashboard');

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
