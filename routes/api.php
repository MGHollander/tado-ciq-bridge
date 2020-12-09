<?php

use App\Http\Controllers\Api\TadoController;
use App\Http\Controllers\Api\TadoZonesController;
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

    Route::get('/zones/{homeId}', [TadoZonesController::class, 'zones']);
});
