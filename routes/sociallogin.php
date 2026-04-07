<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\SocialLoginController;
use App\Helpers\helper;
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
 Route::get('checklogin/google/callback-{logintype}', [SocialLoginController::class, 'check_login']);

 //Login with facebook
 Route::get('checklogin/facebook/callback-{logintype}', [SocialLoginController::class, 'check_login']);
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::post('/social_login', [SocialLoginController::class, 'socialloginsettings']);
    Route::get('login/google-{type}', [SocialLoginController::class, 'redirectToGoogle']);
    Route::get('login/facebook-{type}', [SocialLoginController::class, 'redirectToFacebook']);
  
   
});
$storefrontSocialRoutes = function () {
    //Login with Google
    Route::get('/login/google-{type}', [SocialLoginController::class, 'redirectToGoogle']);

    // //Login with facebook
    Route::get('/login/facebook-{type}', [SocialLoginController::class, 'redirectToFacebook']);
};

Route::group(['namespace' => "front", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor}', 'middleware' => 'FrontMiddleware'], $storefrontSocialRoutes);
Route::group(['namespace' => "front", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontSocialRoutes);
Route::group([
    'namespace' => "front",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontSocialRoutes);
