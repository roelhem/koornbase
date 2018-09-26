<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 14:17
 */


Route::name('graphql.')/*->middleware(['auth:api'])*/->group(function() {

    Route::match(['get','post'],'','\Roelhem\GraphQL\Http\Controllers\GraphQLController@endpoint');

    // Queries & Mutations
    /*Route::match(['get','post'], '', 'GraphQLController@query')
        ->name('query');*/

});

