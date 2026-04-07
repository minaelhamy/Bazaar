<?php

use App\Http\Controllers\addons\CurrencyController;
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

        //currency-settings
        Route::group(['prefix' => 'currency-settings'], function () {
            Route::get('/add', [CurrencyController::class, 'add']);
            Route::post('/store', [CurrencyController::class, 'store']);
            Route::get('/setdefault-{code}/{status}', [CurrencyController::class, 'setdefault']);

            Route::middleware('adminmiddleware')->group(function () {
                Route::get('/delete-{id}/{status}', [CurrencyController::class, 'delete']);
                Route::get('/bulk_delete', [CurrencyController::class, 'bulk_delete']);
                Route::get('/changestatus-{code}/{status}', [CurrencyController::class, 'changestatus']);
            });

            Route::middleware('VendorMiddleware')->group(function () {
                Route::get('/currencystatus-{code}/{status}', [CurrencyController::class, 'currency_setting_status']);
            });
        });

        //currencys 
        Route::group(['prefix' => 'currencys'], function () {
            Route::get('/', [CurrencyController::class, 'currency_data']);
            Route::get('/currency_add', [CurrencyController::class, 'currency_add']);
            Route::post('/currency_store', [CurrencyController::class, 'currency_store']);
            Route::get('/currency_edit-{id}', [CurrencyController::class, 'currency_edit']);
            Route::post('/currency_update-{id}', [CurrencyController::class, 'currency_update']);
            Route::get('/delete-{id}/{status}', [CurrencyController::class, 'currency_delete']);
            Route::get('/currencystatus-{code}/{status}', [CurrencyController::class, 'currencystatus']);
            Route::get('/bulk_delete', [CurrencyController::class, 'currency_bulk_delete']);
        });
    });
});

//fornt-currency
Route::get('currency/change', [CurrencyController::class, 'change'])->name('changeCurrency');
