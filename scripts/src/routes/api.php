<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\ParkingLotController;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

Route::group(['prefix' => 'parking_lot'], function () {
    Route::post('/ticket', [ParkingLotController::class, 'createTicket']);
    Route::get('/board', [ParkingLotController::class, 'getParkingBoard']);
});

Route::get('health', HealthCheckJsonResultsController::class);

Route::get('notifyEndingSessions', [ParkingLotController::class, 'notifyEndingSessions']);
