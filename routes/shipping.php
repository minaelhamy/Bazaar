<?php

use App\Http\Controllers\addons\ShippingController;
use Illuminate\Support\Facades\Route;
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
            Route::group(['prefix' => 'shipping'], function () {
                Route::get('/add', [ShippingController::class, 'add']);
                Route::get('/edit-{id}', [ShippingController::class, 'edit']);
                Route::post('/save', [ShippingController::class, 'save']);
                Route::post('/update-{id}', [ShippingController::class, 'update']);
                Route::get('/status_change-{id}/{status}', [ShippingController::class, 'status_update']);
                Route::get('/delete-{id}', [ShippingController::class, 'delete']);
                Route::post('/reorder_shipping', [ShippingController::class, 'reorder_shipping']);
                Route::get('/bulk_delete', [ShippingController::class, 'bulk_delete']);
            });
        });
    });
});
