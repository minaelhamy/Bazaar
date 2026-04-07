<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\QuestionAnswerController;

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

//product_question_answer


Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::middleware('VendorMiddleware')->group(function () {
            Route::get('/question_answer', [QuestionAnswerController::class, 'question_answer']);
            Route::post('/product_answer', [QuestionAnswerController::class, 'product_answer']);
            Route::get('/question_answer/delete-{id}', [QuestionAnswerController::class, 'delete']);
            Route::get('/question_answer/bulk_delete', [QuestionAnswerController::class, 'bulk_delete']);
            Route::get('/service_question_answer', [QuestionAnswerController::class, 'services_question_answer']);
        });
    });
});



$storefrontQuestionRoutes = function () {
    Route::post('/product_question_answer', [QuestionAnswerController::class, 'product_question_answer']);
};

Route::group(['namespace' => "front", 'domain' => env('WEBSITE_HOST'), 'prefix' => '{vendor}', 'middleware' => 'FrontMiddleware'], $storefrontQuestionRoutes);
Route::group(['namespace' => "front", 'domain' => '{store_subdomain}.' . env('WEBSITE_HOST'), 'middleware' => 'FrontMiddleware'], $storefrontQuestionRoutes);
Route::group([
    'namespace' => "front",
    'domain' => '{custom_domain}',
    'middleware' => 'FrontMiddleware',
    'where' => ['custom_domain' => '^(?!([^.]+\\.)*' . preg_quote(env('WEBSITE_HOST'), '/') . '$).+'],
], $storefrontQuestionRoutes);
