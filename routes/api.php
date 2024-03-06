<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Content-Type');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');

Route::post('get-otp', [AuthenticationController::class,'getOtp']);
Route::post('verify-otp', [AuthenticationController::class,'verifyOtp']);
Route::post('register', [AuthenticationController::class,'register']);

