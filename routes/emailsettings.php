<?php

use App\Http\Controllers\addons\EmailSettingsController;
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
        Route::post('/emailsettings', [EmailSettingsController::class, 'emailsettings']);
        Route::post('/emailmessagesettings', [EmailSettingsController::class, 'emailmessagesettings']);
        Route::post('/testmail', [EmailSettingsController::class, 'testmail']);
    });
});
