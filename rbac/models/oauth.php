<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 16:47
 */


Rbac::group('oauth:', function() {


    Rbac::group('apps:', function() {
        Rbac::crudAbilities(\App\OAuth\App::class, 'crud');
    });



    Rbac::group('clients:', function() {
        Rbac::crudAbilities(\App\OAuth\Client::class, 'crud');
    });



    Rbac::group('tokens:', function() {
        Rbac::crudAbilities(\App\OAuth\Token::class, 'crud');
    });



    Rbac::group('auth-code:', function() {
        Rbac::crudAbilities(\App\OAuth\AuthCode::class, 'crud');
    });



    Rbac::group('personal-access-client:', function() {
        Rbac::crudAbilities(\App\OAuth\PersonalAccessClient::class, 'crud');
    });



});