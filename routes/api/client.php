<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TripController;

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

Route::post('client/login',[LoginController::class, 'clientLogin'])->name('clientLogin');
Route::post('client/signup',[RegisterController::class, 'clientRegister'])->name('clientRegister');
Route::post('client/verify_otp',[OtpController::class, 'verifyClientOtp'])->name('verifyOtp');

Route::group( ['prefix' => 'client','middleware' => ['auth:client-api','scopes:client'] ],function(){
 
    // authenticated staff routes here 
    Route::get('select_trip', [TripController::class, 'tripType'])->name('tripType');
    Route::post('fined_driver', [TripController::class, 'findDriver'])->name('findDriver');
    Route::get('select_trip', [TripController::class, 'tripType'])->name('tripType');

  
});