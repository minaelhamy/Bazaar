<?php
use App\Http\Controllers\addons\FirebaseController;
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
        //   notification
        Route::group(
            ['prefix' => 'notification'],
            function () {
                Route::get('/', [FirebaseController::class, 'index']);
                Route::get('/add', [FirebaseController::class, 'add']);
                Route::post('/save', [FirebaseController::class, 'save']);
                Route::post('/savekey', [FirebaseController::class, 'savekey']);
                Route::get('/resend-{id}', [FirebaseController::class, 'resend']);
                Route::get('/delete-{id}', [FirebaseController::class, 'delete']);

                
            }
        );
    });
});
