<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\ToyyibpayController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::middleware('VendorMiddleware')->group(function () {
            Route::group(
                ['prefix' => 'plan'],
                function () {
                    Route::post('buyplan/toyyibpay', [ToyyibpayController::class, 'toyyibpayrequest']);
                }
            );
        });
    });
});


Route::group(['namespace' => "front", 'middleware' => 'FrontMiddleware'], function () {
    Route::post('/orders/toyyibpayrequest', [ToyyibpayController::class, 'front_toyyibpayrequest']);
});