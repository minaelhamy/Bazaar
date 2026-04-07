<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\VendorImportController;
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
            Route::middleware('adminmiddleware')->group(
                function () {
                    // VENDORS
                    Route::group(
                        ['prefix' => 'users'],
                        function () {
                            Route::get('import',[VendorImportController::class, 'index']);
                            Route::get('exportvendor',[VendorImportController::class, 'exportvendor']);
                            Route::get('generate_city_pdf',[VendorImportController::class, 'generatepdf']);
                            Route::post('import_vendor', [VendorImportController::class, 'import']);
                        }
                    );
                }
            );
        }
    );
});