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

Route::get('/settings', 'SettingsController@index')->name('settings');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::view('/sitemap', 'sitemap')->name('sitemap');




// Routes for the form-model-select components
Route::prefix('select')->group(function() {
    Route::get('/group-category', 'Select\GroupCategoryController@index')->name('select.group-categories');
    Route::get('/group','Select\GroupController@index')->name('select.groups');
});



// Routes for the form-model-select components
Route::prefix('tags')->group(function() {
    Route::get('/group-category', 'Tags\GroupCategoryController@index')->name('tags.group-categories');


    Route::get('/group', 'Tags\GroupController@index')->name('tags.group.index');
    Route::get('/group/{group}', 'Tags\GroupController@show')->name('tags.group.show');
});



// Routes for the Socialite/OAuth2 connections.
Route::prefix('login')->group(function() {

    Route::get('/{provider}', 'Auth\SocialController@redirectToProvider')->name('login.social');
    Route::get('/{provider}/callback', 'Auth\SocialController@handleProviderCallback')->name('login.social.callback');

});







// Routes for editing your own data
Route::prefix('me')->group(function() {

    Route::get('/','MeController@index')->name('me');

});











// Routes for people management
Route::namespace('People')->prefix('people')->group(function() {

    Route::get('/','SearchController@index')->name('people.index');
    Route::get('/search', 'SearchController@search')->name('people.search');
    Route::get('/search/groups', 'SearchController@group')->name('people.search.groups');

    Route::get('/groups','GroupSearchController@index')->name('people.groups.index');
    Route::get('/groups/search','GroupSearchController@search')->name('people.groups.search');

    Route::get('/groups/create', 'GroupController@create')->name('people.groups.create');
    Route::post('/groups/create', 'GroupController@store');

    Route::get('/groups/categories/create','GroupCategoryController@create')->name('people.groups.categories.create');
    Route::post('/groups/categories/create','GroupCategoryController@store');

    Route::get('/create', 'PersonController@create')->name('people.person.create');
    Route::post('/create', 'PersonController@store');
    Route::get('/{person}/edit', 'PersonController@edit')->name('people.person.edit');
    Route::post('/{person}/edit', 'PersonController@update');

    Route::get('/{person}','PersonController@show')->name('people.person');

    Route::get('/{person}/memberships', 'MembershipController@index')->name('people.person.membership.index');
    Route::post('/{person}/memberships/new', 'MembershipController@new')->name('people.person.membership.new');
    Route::post('/{person}/memberships/{membership}/start', 'MembershipController@start')->name('people.person.membership.start');
    Route::post('/{person}/memberships/{membership}/end', 'MembershipController@end')->name('people.person.membership.end');

});





// Routes for event management
Route::namespace('Events')->prefix('events')->group(function() {

    Route::get('/','SearchController@index')->name('events.index');

    Route::get('/{event}', 'EventController@show')->name('events.event');

});






// Routes shared display elements
Route::namespace('Display')->prefix('display')->group(function() {
    Route::get('/group/{group}', 'GroupController@group')->name('display.group');
});






// Routes for developers pages
Route::prefix('develop')->group(function() {

    // Pages that assist in the development of the navigation.
    Route::prefix('nav')->group(function() {
        Route::view('/', 'develop.nav.index')->name('develop.nav.index');
    });
});
