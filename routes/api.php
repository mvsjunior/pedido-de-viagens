<?php

use App\Domains\Auth\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});