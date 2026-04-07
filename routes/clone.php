<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\helper;
use App\Http\Controllers\addons\CloneController;
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
    Route::post('clonevendor', [CloneController::class, 'clonevendor']);
    Route::group(
        ['middleware' => 'AuthMiddleware'],
        function () {
            Route::middleware('adminmiddleware')->group(
                function () {
                    // VENDORS
                    Route::group(
                        ['prefix' => 'users'],
                        function () {
                            Route::get('add-{id}', [CloneController::class, 'add']);
                            Route::get('clone-{id}', [CloneController::class, 'clonevendor']);
                        }
                    );
                }
            );
        }
    );
});