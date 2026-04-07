<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\GoogleLoginController;
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


//Login with Google
Route::get('checklogin/google/callback-{logintype}', [GoogleLoginController::class, 'check_login']);


Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::post('/google_login', [GoogleLoginController::class, 'googleloginsettings']);

    //Login with Google
    Route::get('login/google-{type}', [GoogleLoginController::class, 'redirectToGoogle']);
});

$storefrontGoogleRoutes = function () {
    //Login with Google
    Route::get('/login/google-{type}', [GoogleLoginController::class, 'redirectToGoogle']);
};

Route::group(['namespace' => "web", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor_slug}', 'middleware' => 'FrontMiddleware'], $storefrontGoogleRoutes);
Route::group(['namespace' => "web", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontGoogleRoutes);
Route::group([
    'namespace' => "web",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontGoogleRoutes);
