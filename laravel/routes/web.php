<?php

use App\Http\Livewire\RegisterForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');

Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Verification
Route::get('dashboard', [DashboardController::class,'index'])->middleware(['auth']); 
Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('verify.user'); 
