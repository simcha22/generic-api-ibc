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
//,'user_confirmation'

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/internal/generic/v2', [\App\Http\Controllers\Api\ExecGenericApiController::class, 'index']);
    Route::post('/generic/with_sql', [\App\Http\Controllers\Api\ExecGenericApiWithSqlController::class, 'index']);
    Route::post('/general/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('dashboard/getDashboardData', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
});

Route::post('/general/login', [\App\Http\Controllers\Auth\AuthController::class, 'store']);
Route::post('/internal/generic/v2/open', [\App\Http\Controllers\Api\ExecGenericApiOpenController::class, 'index']);
Route::post('/internal/generic/open/with_sql', [\App\Http\Controllers\Api\ExecGenericApiOpenWithSqlController::class, 'index']);

Route::get('/general/okta/login', [\App\Http\Controllers\Auth\AuthOktaController::class, 'okta']);
Route::get('/general/okta/login/redirect', [\App\Http\Controllers\Auth\AuthOktaController::class, 'store']);

