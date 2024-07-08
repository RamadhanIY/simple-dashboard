<?php

use App\Http\Livewire\RegisterForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;

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

// Forget Password
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::redirect('/dashboard', '/projects')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::put('projects/{project}/description', [ProjectController::class, 'updateProjectDescription'])->name('projects.update_description');
    Route::put('projects/{project}/deadline', [ProjectController::class, 'updateProjectDeadline'])->name('projects.update_deadline');
    Route::put('projects/{project}/projectname', [ProjectController::class, 'updateProjectName'])->name('projects.update_name');
    Route::post('/projects/{project}/upload', [ProjectController::class, 'uploadFile'])->name('projects.upload_file');
    Route::get('/projects/{project}/files/{file}/download', [ProjectController::class, 'downloadFile'])->name('projects.files.download');
    Route::put('/projects/{project}/files/{file}', [ProjectController::class, 'updateFile'])->name('projects.files.update');
    Route::delete('/projects/{project}/files/{file}', [ProjectController::class, 'deleteFile'])->name('projects.files.delete');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});

Route::get('account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('verify.user'); 
