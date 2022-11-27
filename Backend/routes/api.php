<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\GerentesController;
use App\Http\Controllers\GruposController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::apiResources([
        'gerentes' => GerentesController::class,
        'clientes' => ClientesController::class,
        'grupos' => GruposController::class
    ]);
});

Route::post('/v1/gerentes/login', [GerentesController::class, 'login']);