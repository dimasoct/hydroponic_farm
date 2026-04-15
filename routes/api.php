<?php

use App\Http\Controllers\Api\ActuatorDoneController;
use App\Http\Controllers\Api\SensorController;
use Illuminate\Support\Facades\Route;

// Endpoint untuk Raspberry Pi kirim data sensor
Route::post('/sensor-data', [SensorController::class, 'store']);

// Endpoint untuk Raspberry Pi lapor bahwa aktuator sudah selesai (timer habis)
Route::patch('/actuator-done/{id}', [ActuatorDoneController::class, 'update']);
