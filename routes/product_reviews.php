<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\addons\ProductReviewsController;

$storefrontProductReviewRoutes = function () {
    Route::post('/rattingmodal', [ProductReviewsController::class, 'rattingmodal']);
};

Route::group(['namespace' => "front", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor}', 'middleware' => 'FrontMiddleware'], $storefrontProductReviewRoutes);
Route::group(['namespace' => "front", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontProductReviewRoutes);
Route::group([
    'namespace' => "front",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontProductReviewRoutes);
