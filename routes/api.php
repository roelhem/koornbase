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

    Route::apiResources([
        'users' => 'Api\UserController',
        'persons' => 'Api\PersonController',
        'groups' => 'Api\GroupController',
        'group-categories' => 'Api\GroupCategoryController',
        'group-email-address' => 'Api\GroupEmailAddressController',
        'certificates' => 'Api\CertificateController',
        'certificate-categories' => 'Api\CertificateCategoryController'
    ]);
});
