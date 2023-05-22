<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Auth;

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
 
Route::middleware(['guest'])->group(function () {
    
    Route::post('driver/login', [LoginController::class, 'driverLogin'])->name('driverLogin');
    Route::post('driver/signup', [RegisterController::class, 'driverRegister'])->name('driverRegister');
    Route::post('driver/verify_otp', [OtpController::class, 'verifyDriverOtp'])->name('verifyDriverOtp');
});

 
Route::group(['prefix' => 'driver', 'middleware' => ['auth:driver-api', 'scopes:driver' ]], function () {
    // authenticated staff routes here 
    Route::get('/dashboard',function(){
     
    });

    Route::post('vehicle_registration', [VehicleController::class, 'updateVehicleRegistration']);
});
