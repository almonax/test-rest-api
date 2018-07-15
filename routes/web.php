<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* Server side */
Route::group(['prefix' => 'server'], function () {
    Route::get('/users', 'UserListController@index')->name('userList');
    Route::get('/users/getUsers', 'UserListController@getUsers');
});

/* Client side */
Route::group(['prefix' => 'client'], function () {
    Route::get('/users', 'ApiClientController@index')->name('clientIndex');
    Route::get('/users/getUsers', 'ApiClientController@getUsers');
    Route::get('/users/create', 'ApiClientController@create')->name('clientCreate');
    Route::post('/users/store', 'ApiClientController@store')->name('clientStore');
    Route::get('/users/edit/{id}', 'ApiClientController@edit')->name('clientEdit');
    Route::post('/users/update/{id}', 'ApiClientController@update')->name('clientUpdate');;
    Route::get('/users/delete/{id}', 'ApiClientController@delete')->name('clientDelete');
});