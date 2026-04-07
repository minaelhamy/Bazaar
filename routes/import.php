<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\ImportController;

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
                    Route::get('/generatepdf', [ImportController::class, 'generatepdf']);
                    Route::get('/generatetaxpdf', [ImportController::class, 'generatetaxpdf']);
                    Route::post('/importproduct', [ImportController::class, 'importproduct']);
                    Route::get('/exportproduct',[ImportController::class,'exportproduct']);
                    Route::group(
                        ['prefix' => 'products'],
                        function () {
                            Route::get('/import', [ImportController::class, 'import']);
                        }
                    );
                }
            );
        }
    );
});