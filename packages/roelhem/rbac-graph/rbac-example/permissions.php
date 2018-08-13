<?php

Rbac::group('auth:', function() {

    Rbac::permission('login')->title('Login')
        ->description('Permission to login on the website');


    Rbac::permissionSet('password-reset:')->assign([
        Rbac::permission('password-reset:request')->title('Request a password-reset for the users account.'),
        Rbac::permission('password-reset:reset')->title('Reset the password of the users account.')
    ]);

});

Rbac::routePermission('login');
Rbac::routePermission('logout');


Rbac::group('users:', function() {


    Rbac::crudAbilities(\App\User::class, null,
        ['view','create','update','delete','update.passport']
    );

    // blocking/unblocking
    Rbac::permissionSet('blocking')->assign([
        Rbac::modelAbility('block', \App\User::class),
        Rbac::modelAbility('unblock', \App\User::class),
        Rbac::modelAbility('view-block-status', \App\User::class)
    ]);

    // Testing
    Rbac::modelAbility('mock', \App\User::class);


});




Rbac::group('groups:', function () {

    Rbac::crudAbilities(\App\Group::class);

});




Rbac::group('rbac-graph:', function() {

    Rbac::permission('view')->title('Rbac-graph: view')
        ->description('View the structure of the Rbac-graph.');

    Rbac::group('node:', function () {

        Rbac::crudAbilities(\Roelhem\RbacGraph\Database\Node::class);

        Rbac::get('crud.view')->title('Rbac-graph node: view')
            ->description('View the title and description of a node.');
        Rbac::get('crud.create')->title('Rbac-graph node: create')
            ->description('Create new node and add to the rbac-graph.');
        Rbac::get('crud.update')->title('Rbac-graph node: update')
            ->description('Edit the title and description of a node.');
        Rbac::get('crud.delete')->title('Rbac-graph node: delete')
            ->description('Remove a node from the rbac-graph.');

    });

    Rbac::group('analyse:', function() {

        Rbac::group('authorization:', function() {
            Rbac::permission('nodes')->title('Rbac-graph: analyze nodes.');
            Rbac::permission('users');
            Rbac::permission('groups');

        });

        Rbac::group('inverse-authorization:', function() {
            Rbac::permission('nodes')->title('Rbac-graph: analyze nodes.');
            Rbac::permission('models');
        });
    });

});
