<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\TelegramController;
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
        Route::middleware('VendorMiddleware')->group(function () {
            Route::get('/telegram_settings', [TelegramController::class, 'index']);
            Route::post('telegrammessage/business_api', [TelegramController::class, 'business_api']);
            Route::post('telegrammessage/order_message_update', [TelegramController::class, 'order_message_update']);
        });
    });
});

$storefrontTelegramRoutes = function () {
    Route::get('/telegram/{order_number}', [TelegramController::class, 'telegram']);
};

Route::group(['namespace' => "front", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor}', 'middleware' => 'FrontMiddleware'], $storefrontTelegramRoutes);
Route::group(['namespace' => "front", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontTelegramRoutes);
Route::group([
    'namespace' => "front",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontTelegramRoutes);
