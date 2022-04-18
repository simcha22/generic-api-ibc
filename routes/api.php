<?php

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum','user_confirmation'])->group(function () {
    Route::post('/generic', [\App\Http\Controllers\Api\ExecGenericApiController::class, 'index']);
    Route::post('/generic/with_sql', [\App\Http\Controllers\Api\ExecGenericApiWithSqlController::class, 'index']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
});

Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'store']);
Route::post('/generic/open', [\App\Http\Controllers\Api\ExecGenericApiOpenController::class, 'index']);
Route::post('/generic/open/with_sql', [\App\Http\Controllers\Api\ExecGenericApiOpenWithSqlController::class, 'index']);
