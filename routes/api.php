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
    Route::group(['prefix' => 'token', 'namespace' => 'Token'], function () {
        Route::post('client', [
            'uses' => 'AccessTokenController@getClientToken',
            'as' => 'token.client'
        ]);

        Route::group(['prefix' => 'user', 'middleware' => 'client_credentials:private'], function () {
            Route::post('/', [
                'uses' => 'AccessTokenController@getUserToken',
                'as' => 'token.user'
            ]);

            Route::delete('/', [
                'uses' => 'AccessTokenController@revokeUserToken',
                'as' => 'token.user.revoke'
            ]);
        });
    });

    Route::group(['prefix' => 'user', 'namespace' => 'User', 'middleware' => 'auth:idm'], function () {
        Route::get('/', [
            'uses' => 'UserController@getUserInfo',
            'as' => 'user.info'
        ]);
    });
});

