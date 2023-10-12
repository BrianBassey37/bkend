<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('api')->group(function(){
    
    Route::prefix('v1/')->group(function(){
        
        Route::get('/', function () {
            abort(403, 'Unauthorized');
        });       

        Route::apiResource('schools', 'App\Http\Controllers\API\SchoolController');
        Route::apiResource('departments', 'App\Http\Controllers\API\DepartmentController');
        Route::apiResource('students', 'App\Http\Controllers\API\StudentController');
        Route::apiResource('staff', 'App\Http\Controllers\API\StaffController');
        Route::apiResource('courses', 'App\Http\Controllers\API\CourseController');
        Route::apiResource('results', 'App\Http\Controllers\API\ResultController');

        Route::put('/approveResult/{id}', 'App\Http\Controllers\API\ResultController@approveResult');
        Route::put('/publishResult/{id}', 'App\Http\Controllers\API\ResultController@publishResult');

        Route::group(['prefix' => 'auth'], function () {
            Route::post('login', 'App\Http\Controllers\API\AuthController@login');
        });

    }); 

});
