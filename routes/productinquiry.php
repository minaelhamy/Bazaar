<?php

use App\Http\Controllers\addons\ProductInquiryController;
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

Route::group(['namespace' => 'front'], function () {
    Route::post('product_inquiry', [ProductInquiryController::class, 'product_inquiry']);
});


Route::group(['prefix' => 'admin', 'namespace' => "admin"], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::group(['prefix' => 'product_inquiry'], function () {
            Route::get('/', [ProductInquiryController::class, 'index']);
            Route::get('delete-{id}', [ProductInquiryController::class, 'delete']);
            Route::get('change_status-{id}/{status}', [ProductInquiryController::class, 'change_status']);
            Route::get('bulk_delete', [ProductInquiryController::class, 'bulk_delete']);
        });
    });
});
