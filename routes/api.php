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

    Route::apiResources([
        'users' => 'Api\UserController',
        'persons' => 'Api\PersonController',
        'groups' => 'Api\GroupController',
        'group-categories' => 'Api\GroupCategoryController',
        'group-email-address' => 'Api\GroupEmailAddressController',
        'certificates' => 'Api\CertificateController',
        'certificate-categories' => 'Api\CertificateCategoryController',
        'koornbeurs-cards' => 'Api\KoornbeursCardController'
    ]);

    Route::post('persons/{person}/attach','Api\PersonController@attach')->name('persons.attach');
    Route::post('persons/{person}/detach', 'Api\PersonController@detach')->name('persons.detach');
    Route::post('persons/{person}/sync', 'Api\PersonController@sync')->name('persons.sync');

    Route::post('groups/{group}/attach','Api\GroupController@attach')->name('groups.attach');
    Route::post('groups/{group}/detach', 'Api\GroupController@detach')->name('groups.detach');
    Route::post('groups/{group}/sync', 'Api\GroupController@sync')->name('groups.sync');

    Route::get('me','Api\MeController@me');
});
