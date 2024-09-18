<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CarServiceController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
    Route::get('/{id}', [CustomerController::class, 'show']);
    Route::put('/{id}', [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
});

Route::prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'index']);
    Route::post('/', [CarController::class, 'store']);
    Route::get('/{id}', [CarController::class, 'show']);
    Route::put('/{id}', [CarController::class, 'update']);
    Route::delete('/{id}', [CarController::class, 'destroy']);
});

Route::get('/search-customer', [CarServiceController::class, 'searchCustomer']);
Route::get('/initiate-services/{carId}', [CarServiceController::class, 'initiateServices']);
Route::post('/store-services', [CarServiceController::class, 'storeServices']);

Route::get('/current-jobs', [CarServiceController::class, 'currentJobs']);
Route::post('/update-job-status/{id}', [CarServiceController::class, 'updateJobStatus']);
