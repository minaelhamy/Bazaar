<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\FakeSalesNotificationController;
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
        Route::post('settings/fake_sales_notification', [FakeSalesNotificationController::class, 'fake_sales_notification']);
    });
});


Route::group(['namespace' => 'front', 'middleware' => 'FrontMiddleware'], function () {
	Route::post('/get_notification_data', [FakeSalesNotificationController::class, 'get_notification_data']);
});