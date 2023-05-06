<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\VehicleController;

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

Route::post('driver/login', [LoginController::class, 'driverLogin'])->name('driverLogin');

Route::post('driver/signup', [RegisterController::class, 'driverRegister'])->name('driverRegister');

Route::group(['prefix' => 'driver', 'middleware' => ['auth:driver-api', 'scopes:driver']], function () {
    // authenticated staff routes here 
    Route::get('dashboard', [LoginController::class, 'clientDashboard']);

    Route::post('vehicle_registration', [VehicleController::class, 'updateVehicleRegistration']);
});


