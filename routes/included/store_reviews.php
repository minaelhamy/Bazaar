<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\included\TestimonialController;


Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(
        ['middleware' => 'AuthMiddleware'],
        function () {
            //testimonial
            Route::group(
                ['prefix' => 'testimonials'],
                function () {
                    Route::get('/', [TestimonialController::class, 'index']);
                    Route::get('/add', [TestimonialController::class, 'add']);
                    Route::post('/save', [TestimonialController::class, 'save']);
                    Route::get('/edit-{id}', [TestimonialController::class, 'edit']);
                    Route::post('/update-{id}', [TestimonialController::class, 'update']);
                    Route::get('/delete-{id}', [TestimonialController::class, 'delete']);
                    Route::post('/reorder_testimonials', [TestimonialController::class, 'reorder_testimonials']);
                    Route::get('/bulk_delete', [TestimonialController::class, 'bulk_delete']);
                }
            );
        }
    );
});
