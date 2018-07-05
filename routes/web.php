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


Auth::routes();


// Routes for the Socialite/OAuth2 connections.
Route::prefix('login')->group(function() {

    Route::get('/{provider}', 'Auth\SocialController@redirectToProvider')->name('login.social');
    Route::get('/{provider}/callback', 'Auth\SocialController@handleProviderCallback')->name('login.social.callback');

});

Route::get('/', function() {
    return "Homepage";
});

Route::any('/{path?}', 'AppController@index')->where('path','.*');
