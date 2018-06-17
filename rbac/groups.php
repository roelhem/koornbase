<?php


Rbac::group('models:',function() {


    Rbac::group('group-categories:', function () {
        Rbac::crudAbilities(\App\GroupCategory::class, 'crud',[
            'create','view','update','delete','force-delete'
        ]);

        Rbac::task('Manage')->assign('crud.create','crud.view','crud.update','crud.delete');

        Rbac::task('Clean-up')->assign('crud.view','crud.update','crud.delete','crud.force-delete');
    });

    Rbac::group('groups:', function () {

        Rbac::crudAbilities(\App\Group::class, 'crud', [
            'create','view','update','delete','force-delete'
        ]);

        Rbac::task('Manage')->assign('crud.create','crud.view','crud.update','crud.delete');

        Rbac::task('Clean-up')->assign('crud.view','crud.update','crud.delete','crud.force-delete');

    });


    Rbac::group('group-email-addresses:', function() {
        Rbac::crudAbilities(\App\GroupEmailAddress::class);

        Rbac::task('Manage')->assign('crud');
    });


});