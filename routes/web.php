<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/auth/login');
});

Route::get('/auth/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login.submit');

Route::middleware(['auth'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);

    Route::resource('services', ServiceController::class);

    Route::resource('patients', PatientController::class);

    Route::resource('doctors', DoctorController::class);

    Route::resource('schedules', ScheduleController::class);

    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::post('{id}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
        Route::get('{appointment}/print', [AppointmentController::class, 'print'])->name('print');
    });

    Route::prefix('examinations')->name('examinations.')->group(function () {
        Route::get('/{appointment}/create', [ExaminationController::class, 'create'])->name('create');
        Route::post('/', [ExaminationController::class, 'store'])->name('store');
        Route::post('/services', [ExaminationController::class, 'addService'])->name('addService');
        Route::delete('/services/{id}', [ExaminationController::class, 'destroy'])->name('deleteService');
    });
});
