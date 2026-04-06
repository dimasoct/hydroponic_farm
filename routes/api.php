<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;

Route::post('/sensor-data', [SensorController::class, 'store']);