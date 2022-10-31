<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::group(['namespace' => 'Tokens'], function () {
        Route::post('/token/client', [
            'uses' => 'AccessTokenController@getClientToken',
            'as' => 'token'
        ]);

        Route::post('/token/user', [
            'middleware' => 'client_credentials:private',
            'uses' => 'AccessTokenController@getUserToken',
            'as' => 'token'
        ]);
    });
});

