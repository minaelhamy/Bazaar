<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\CustomStatusController;

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



//  ------------------------------- ----------- -----------------------------------------   //
//  -------------------------------  FOR ADMIN  -----------------------------------------   //
//  ------------------------------- ----------- -----------------------------------------   //	

Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(
        ['middleware' => 'AuthMiddleware'],
        function () {
            Route::middleware('VendorMiddleware')->group(
                function () {
                    Route::group(
                        ['prefix' => 'custom_status'],
                        function () {
                            Route::get('add', [CustomStatusController::class, 'add']);
                            Route::post('save', [CustomStatusController::class, 'save']);
                            Route::get('delete-{id}', [CustomStatusController::class, 'delete']);
                            Route::get('/', [CustomStatusController::class, 'index']);
                            Route::get('edit-{id}', [CustomStatusController::class, 'edit']);
                            Route::post('update-{id}', [CustomStatusController::class, 'update']);
                            Route::get('change_status-{id}/{status}', [CustomStatusController::class, 'change_status']);
                            Route::post('/reorder_status', [CustomStatusController::class, 'reorder_status']);
                        }
                    );
                }
            );
        }
    );
});
