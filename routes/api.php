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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group([
    'prefix' => '/v1/users'
], function () {
    /* get collection of users */
    Route::get('/', 'ApiServerController@getUsers');

    /* get user by id */
    Route::get('/{id}', 'ApiServerController@getUser');

    /* create user */
    Route::post('/', 'ApiServerController@createUser');

    /* update user */
    Route::put('/{id}', 'ApiServerController@updateUser');
    /* update user avatar */
    Route::post('/{id}/avatar', 'ApiServerController@updateUserAvatar');

    /* delete user */
    Route::delete('/{id}', 'ApiServerController@deleteUser');
});