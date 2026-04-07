<?php


use App\Http\Controllers\addons\included\LanguageController;
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

Route::get('lang/change', [LanguageController::class, 'change'])->name('changeLang');

Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::group(['prefix' => 'language-settings'], function () {
            Route::get('/', [LanguageController::class, 'index']);
            Route::get('/add', [LanguageController::class, 'add']);
            Route::post('/store', [LanguageController::class, 'store']);
            Route::get('/{code}', [LanguageController::class, 'index']);

            Route::middleware('adminmiddleware')->group(function () {
                Route::post('/update', [LanguageController::class, 'storeLanguageData']);
                Route::get('/language/edit-{id}', [LanguageController::class, 'edit']);
                Route::post('/update-{id}', [LanguageController::class, 'update']);
                Route::get('/layout/delete-{id}/{status}', [LanguageController::class, 'delete']);
                Route::get('/status-{id}/{status}', [LanguageController::class, 'status']);
            });

            Route::middleware('VendorMiddleware')->group(function () {
                Route::get('/languagestatus-{code}/{status}', [LanguageController::class, 'languagestatus']);
                Route::get('/setdefault-{code}/{status}', [LanguageController::class, 'setdefault']);
            });
        });
    });
});
