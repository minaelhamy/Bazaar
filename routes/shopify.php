<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\ShopifyController;
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
            // shopify-products
            Route::group(
                ['prefix' => 'shopify-products'],
                function () {
                    Route::get('/', [ShopifyController::class, 'index']);
                    Route::get('add-{id}', [ShopifyController::class, 'add']);
                    Route::post('store', [ShopifyController::class, 'store']);
                    Route::post('update-{id}', [ShopifyController::class, 'store']);
                    Route::get('status-{id}-{status}', [ShopifyController::class, 'status']);
                    Route::get('delete-{id}', [ShopifyController::class, 'delete']);
                    Route::post('reorder_shippingarea', [ShopifyController::class, 'reorder_shippingarea']);
                }
            );

            // shopify-category
            Route::group(
                ['prefix' => 'shopify-category'],
                function () {
                    Route::get('/', [ShopifyController::class, 'shopify_category']);
                    Route::get('add-category-{id}', [ShopifyController::class, 'add_category']);
                }
            );
        });
    });
});