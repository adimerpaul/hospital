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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);
Route::get('/reportHistory/{id}', [\App\Http\Controllers\QueryController::class, 'reportHistory']);
Route::get('/reportReception/{id}', [\App\Http\Controllers\QueryController::class, 'reportReception']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout']);
    Route::post('/upload', [\App\Http\Controllers\UploadController::class, 'upload']);
    Route::post('/me', [\App\Http\Controllers\UserController::class, 'me']);
    Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
    Route::apiResource('products', \App\Http\Controllers\ProductController::class);
    Route::apiResource('lots', \App\Http\Controllers\LotController::class);
    Route::apiResource('patients', \App\Http\Controllers\PatientController::class);
    Route::apiResource('reservations', \App\Http\Controllers\ReservationsController::class);
    Route::apiResource('queries', \App\Http\Controllers\QueryController::class);
    Route::apiResource('medicines', \App\Http\Controllers\MedicineController::class);
    Route::apiResource('numbers', \App\Http\Controllers\NumberController::class);
    Route::apiResource('times', \App\Http\Controllers\TimeController::class);
    Route::apiResource('units', \App\Http\Controllers\UnitController::class);
    Route::apiResource('vias', \App\Http\Controllers\ViaController::class);
});
