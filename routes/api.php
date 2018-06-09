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

Route::name('api.')->middleware(['rbac','auth:api'])->group(function() {

    Route::apiResources([
        'users'                  => 'Api\UserController',
        'persons'                => 'Api\PersonController',
        'person-addresses'       => 'Api\PersonAddressController',
        'person-email-addresses' => 'Api\PersonEmailAddressController',
        'person-phone-numbers'   => 'Api\PersonPhoneNumberController',
        'groups'                 => 'Api\GroupController',
        'group-categories'       => 'Api\GroupCategoryController',
        'group-email-address'    => 'Api\GroupEmailAddressController',
        'certificates'           => 'Api\CertificateController',
        'certificate-categories' => 'Api\CertificateCategoryController',
        'koornbeurs-cards'       => 'Api\KoornbeursCardController',
        'memberships'            => 'Api\MembershipController'
    ]);

    Route::post('persons/{person}/attach','Api\PersonController@attach')->name('persons.attach');
    Route::post('persons/{person}/detach', 'Api\PersonController@detach')->name('persons.detach');
    Route::post('persons/{person}/sync', 'Api\PersonController@sync')->name('persons.sync');

    Route::post('groups/{group}/attach','Api\GroupController@attach')->name('groups.attach');
    Route::post('groups/{group}/detach', 'Api\GroupController@detach')->name('groups.detach');
    Route::post('groups/{group}/sync', 'Api\GroupController@sync')->name('groups.sync');

    Route::get('me','Api\MeController@me')->name('me');


    Route::name('support.')->prefix('support')->group(function() {

        Route::get("countries",'Api\Support\CountryController@index')->name('countries.index');
        Route::get("countries/{country_code}",'Api\Support\CountryController@show')->name('countries.show');

        Route::get("address-formats", 'Api\Support\AddressFormatController@index')->name('address-formats.index');
        Route::get("address-formats/{country_code}", 'Api\Support\AddressFormatController@show')->name('address-formats.show');

    });

});
