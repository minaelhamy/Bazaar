<?php

use App\Http\Controllers\addons\AiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\included\BlogController;

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
        // Blog
        Route::group(
            ['prefix' => 'blogs'],
            function () {
                Route::get('/', [BlogController::class, 'index']);
                Route::get('add', [BlogController::class, 'add']);
                Route::post('save', [BlogController::class, 'save']);
                Route::get('edit-{slug}', [BlogController::class, 'edit']);
                Route::post('update-{slug}', [BlogController::class, 'update']);
                Route::get('delete-{slug}', [BlogController::class, 'delete']);
                Route::post('reorder_blogs', [BlogController::class, 'reorder_blogs']);
                Route::get('bulk_delete', [BlogController::class, 'bulk_delete']);
                Route::post('ai_blogs_generate', [AiController::class, 'ai_blogs_generate']);
            }
        );
    });
});

$storefrontBlogRoutes = function () {
    Route::get('/blogs', [BlogController::class, 'blogs']);
    Route::get('/blogs-{slug}', [BlogController::class, 'blogdetails']);
};

Route::group(['namespace' => "front", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor}', 'middleware' => 'FrontMiddleware'], $storefrontBlogRoutes);
Route::group(['namespace' => "front", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontBlogRoutes);
Route::group([
    'namespace' => "front",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontBlogRoutes);
