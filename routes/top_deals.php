<?php

use App\Http\Controllers\addons\TopdealsController;
use Illuminate\Support\Facades\Route;

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

        Route::get('/top_deals', [TopdealsController::class, 'index']);
        Route::post('/top_deals/update', [TopdealsController::class, 'top_deals']);
        Route::get('/top_deals/delete-{id}', [TopdealsController::class, 'delete']);
        Route::get('/top_deals/bulk_delete', [TopdealsController::class, 'bulk_delete']);
    });
});
