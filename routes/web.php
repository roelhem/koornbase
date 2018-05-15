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

    Route::get('/{person}','PersonController@timeline')->name('people.person');
    Route::get('/{person}/contact', 'PersonController@contact')->name('people.person.contact');

});





// Routes for event management
Route::namespace('Events')->prefix('events')->group(function() {

    Route::get('/','SearchController@index')->name('events.index');

});








// Routes for crud
Route::namespace('Crud')->prefix('crud')->group(function() {
    Route::resource('studies', 'StudyController');
    Route::resource('persons', 'PersonController');
    Route::resource('groups', 'GroupController');
    Route::resource('group-categories', 'GroupCategoryController');
});







// Routes shared display elements
Route::namespace('Display')->prefix('display')->group(function() {
    Route::get('/group/{group}', 'GroupController@group')->name('display.group');
});







// Routes for FullCalendar event-feeds.
Route::namespace('Calendar')->prefix('calendar')->group(function() {
    Route::get('/birthdays', 'BirthdayController@list')->name('calendar.birthdays');

    Route::get('/events', 'EventController@list')->name('calendar.events');
});






// Routes for developers pages
Route::prefix('develop')->group(function() {

    // Pages that assist in the development of the navigation.
    Route::prefix('nav')->group(function() {
        Route::view('/', 'develop.nav.index')->name('develop.nav.index');
    });
});
