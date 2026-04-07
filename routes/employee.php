<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\EmployeeController;
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
        Route::get('/roles', [EmployeeController::class, 'index']);
        Route::get('/roles/add', [EmployeeController::class, 'add']);
        Route::post('/roles/save', [EmployeeController::class, 'save']);
        Route::get('/roles/edit-{id}', [EmployeeController::class, 'edit']);
        Route::post('/roles/update-{id}', [EmployeeController::class, 'update']);
        Route::get('/roles/change_status-{id}/{status}', [EmployeeController::class, 'status']);
        Route::get('/roles/delete-{id}', [EmployeeController::class, 'delete']);
        Route::get('/roles/bulk_delete', [EmployeeController::class, 'bulk_delete']);

        Route::get('/employees', [EmployeeController::class, 'employee_index']);
        Route::get('/employees/add', [EmployeeController::class, 'employee_add']);
        Route::get('/employees/edit-{id}', [EmployeeController::class, 'employee_edit']);
        Route::post('/employees/save', [EmployeeController::class, 'employee_save']);
        Route::post('/employees/update-{id}', [EmployeeController::class, 'employee_update']);
        Route::get('/employees/status-{id}/{status}', [EmployeeController::class, 'employee_status']);
        Route::get('/employees/delete-{id}', [EmployeeController::class, 'employee_delete']);
        Route::get('/employees/bulk_delete', [EmployeeController::class, 'employee_bulk_delete']);
    });
});
