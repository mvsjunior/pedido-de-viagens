<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Auth\Http\Controllers\AuthController;

Route::prefix('travel')->group(base_path('app/Domains/Travel/routes/travel.php'));

// Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});