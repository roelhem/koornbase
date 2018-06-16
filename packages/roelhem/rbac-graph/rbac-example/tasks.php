<?php

Rbac::task('auth:use')->assign([
    'auth.login','auth.password-reset',
    Rbac::routePermission('login'),
    Rbac::routePermission('logout')
]);

Rbac::group('users:', function() {

    Rbac::task('MANAGE')->title('Manage Users')
        ->assign('crud')
        ->assignTo('admin');

    Rbac::task('MONITOR')->title('Monitor Users')
        ->assign('crud.view','view-block-status')
        ->assignTo('group.primary','webmaster');

    Rbac::task('MODERATE')->title('Moderate Users')
        ->assign('crud.view','crud.update','blocking')
        ->assignTo('moderator');

    Rbac::task('DISTRIBUTE')->title('Distribute Users')
        ->assign('crud.create')
        ->assignTo('webmaster');

    Rbac::task('TEST')->title('Test the users')
        ->assign('crud.mock','crud.create')
        ->assignTo('tester','developer-auth');

});


Rbac::group('groups:', function() {

    Rbac::task('MANAGE')->assign('crud');

});