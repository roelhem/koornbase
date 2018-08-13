<?php

Route::namespace("Roelhem\RbacGraph\Http\Controllers")
    ->prefix('graph')
    ->name('rbac-graph.')
    ->group(function() {



    Route::get('/', "HomeController@index")->name('index');




    Route::prefix('nodes')->name('nodes.')->group(function() {
        Route::get('/','NodeController@index')->name('index');
        Route::get('/{node}', 'NodeController@view')->name('view');
    });


});