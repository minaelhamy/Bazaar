<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\included\WhatsappmessageController;
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
    Route::middleware('AuthMiddleware')->group(function () {
        Route::get('/whatsapp_settings', [WhatsappmessageController::class, 'index']);
        Route::post('settings/order_message_update', [WhatsappmessageController::class, 'order_message_update']);
        Route::post('settings/status_message', [WhatsappmessageController::class, 'status_message']);
        Route::post('settings/business_api', [WhatsappmessageController::class, 'business_api']);
    });
});
