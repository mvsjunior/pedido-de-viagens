<?php

use App\Domains\Travel\Http\Controllers\TravelRequestController;

use Illuminate\Support\Facades\Route;

Route::middleware('jwt')->group(function(){
    Route::get('/', [TravelRequestController::class, 'list'])->name('travel.list');
    Route::post('/', [TravelRequestController::class, 'open'])->name('travel.open');
    Route::get('/{id}', [TravelRequestController::class, 'show'])->name('travel.show');
    Route::patch('/{id}', [TravelRequestController::class, 'edit'])->name('travel.edit');
    Route::patch('/{id}/approve', [TravelRequestController::class, 'approve'])->name('travel.approve');
    Route::patch('/{id}/cancel', [TravelRequestController::class, 'cancel'])->name('travel.cancel');
});
