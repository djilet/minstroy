<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('region', 'RegionController@index');

Route::get('building', 'BuildingController@index');
Route::get('building/{id}', 'BuildingController@show');
Route::get('building/image/{filename}', 'BuildingImageController@show');
Route::get('building/video/{filename}', 'BuildingVideoController@show');
Route::get('building/audio/{filename}', 'BuildingAudioController@show');

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('forgot-password', 'ForgotPasswordController@forgotPassword');

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::apiResource('user', 'AdminController');
        Route::apiResource('building', 'BuildingController');
        Route::apiResource('building-type', 'BuildingTypeController');
        Route::apiResource('region', 'RegionController');

        Route::post('file/upload', 'FileController@upload');

        Route::put('building/{id}/approve', 'BuildingController@approve');
        Route::put('building/{id}/reject', 'BuildingController@reject');
    });
});

