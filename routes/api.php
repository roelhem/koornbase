<?php

use Illuminate\Http\Request;

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

Route::name('api.')->group(function() {

    Route::get('me','Api\MeController@me');

    Route::resource('users','Api\UserController');
    Route::resource('persons', 'Api\PersonController');
    Route::resource('groups', 'Api\GroupController');
    Route::resource('group-categories', 'Api\GroupCategoryController');
});
