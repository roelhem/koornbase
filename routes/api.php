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

    Route::apiResource('users','Api\UserController');
    Route::apiResource('persons', 'Api\PersonController');
    Route::apiResource('groups', 'Api\GroupController');
    Route::apiResource('group-categories', 'Api\GroupCategoryController');
    Route::apiResource('group-email-addresses', 'Api\GroupEmailAddressController');
});
