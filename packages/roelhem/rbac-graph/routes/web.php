<?php

Route::namespace("Roelhem\RbacGraph\Http\Controllers")
    ->prefix('graph')
    ->name('rbac-graph.')
    ->group(function() {

    Route::get('/', "HomeController@index");

});