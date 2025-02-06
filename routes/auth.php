<?php
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\CheckAuthenticated;


Route::get('/', [RegisterController::class, 'Acceuil'])->name('Acceuil');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/verify-code', [RegisterController::class, 'verifyCode'])->name('verifyCode');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);


Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(CheckAuthenticated::class);
