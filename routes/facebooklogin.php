<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\FacebookLoginController;

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



//Login with facebook
Route::get('checklogin/facebook/callback-{logintype}', [FacebookLoginController::class, 'check_login']);
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {

    Route::post('/facebook_login', [FacebookLoginController::class, 'facebookloginsettings']);


    //Login with facebook
    Route::get('login/facebook-{type}', [FacebookLoginController::class, 'redirectToFacebook']);
});

$storefrontFacebookRoutes = function () {
    //Login with facebook
    Route::get('/login/facebook-{type}', [FacebookLoginController::class, 'redirectToFacebook']);
};

Route::group(['namespace' => "web", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor_slug}', 'middleware' => 'FrontMiddleware'], $storefrontFacebookRoutes);
Route::group(['namespace' => "web", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontFacebookRoutes);
Route::group([
    'namespace' => "web",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontFacebookRoutes);
