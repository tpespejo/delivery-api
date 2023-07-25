<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EstimateController;
use App\Http\Controllers\Api\TokenController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout',[AuthController::class,'logout']);

Route::get('get-token', [TokenController::class, 'getToken']);

Route::middleware('jwt.verify')->group(function() {
    Route::post('dates',[EstimateController::class,'checkEstimateDates']);
    Route::post('rates',[EstimateController::class,'checkEstimateRates']);
    Route::post('combined-estimates',[EstimateController::class,'getCombinedEstimates']);
   
});
