<?php

use App\Http\Controllers\Api\TadoController;
use App\Http\Controllers\Api\TadoDevicesController;
use App\Http\Controllers\Api\TadoHomesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [TadoController::class, 'me']);
    Route::get('/homes/{homeId}/devices', [TadoHomesController::class, 'devices']);
    Route::get('/devices/{deviceId}/hi', [TadoDevicesController::class, 'hi']);
});
