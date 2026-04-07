<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\AgeVerificationController;


Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::post('/age_verification', [AgeVerificationController::class, 'age_verification']);
    });
});
