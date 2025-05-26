<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

    Route::get('/dashboard', function () {
        return view('layouts.index');
    });
});
