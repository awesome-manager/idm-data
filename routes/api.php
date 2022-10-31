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
    });

    Route::post('/token/user', [
        'uses' => 'AccessTokenController@issueToken',
        'as' => 'token'
    ]);

    Route::get('/authorize', [
        'uses' => 'AuthorizationController@authorize',
        'as' => 'authorizations.authorize',
        'middleware' => 'web',
    ]);

    Route::middleware(['auth'])->group(function () {
        Route::post('/token/refresh', [
            'uses' => 'TransientTokenController@refresh',
            'as' => 'token.refresh',
        ]);

        Route::post('/authorize', [
            'uses' => 'ApproveAuthorizationController@approve',
            'as' => 'authorizations.approve',
        ]);

        Route::delete('/authorize', [
            'uses' => 'DenyAuthorizationController@deny',
            'as' => 'authorizations.deny',
        ]);

        Route::post('/personal-access-tokens', [
            'uses' => 'PersonalAccessTokenController@store',
            'as' => 'personal.tokens.store',
        ]);
    });
});

