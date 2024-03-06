<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
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



header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');

Route::post('get-otp', [AuthenticationController::class,'getOtp']);
Route::post('verify-otp', [AuthenticationController::class,'verifyOtp']);
Route::post('register', [AuthenticationController::class,'register']);
