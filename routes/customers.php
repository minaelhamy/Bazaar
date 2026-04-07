<?php

use App\Http\Controllers\addons\CustomerController;
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
        // Customers
        Route::group(
            ['prefix' => 'customers'],
            function () {
                Route::get('/', [CustomerController::class, 'index']);
                Route::get('add', [CustomerController::class, 'add']);
                Route::post('save', [CustomerController::class, 'save_customer']);
                Route::get('edit-{id}', [CustomerController::class, 'edit']);
                Route::post('update-{slug}', [CustomerController::class, 'update']);
                Route::get('status-{id}/{status}', [CustomerController::class, 'status']);
                Route::get('orders-{id}', [CustomerController::class, 'orders']);
                Route::get('delete-{id}', [CustomerController::class, 'deletecustomer']);
                Route::get('bulk_delete', [CustomerController::class, 'bulk_delete_customer']);
            }
        );
    });
});
