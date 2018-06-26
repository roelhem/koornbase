<?php


Rbac::group('persons:', function() {

    Rbac::crudAbilities(\App\Person::class, 'crud');

    Rbac::group('trashed:', function () {

        Rbac::modelAbility('view.trashed', \App\GroupCategory::class, 'view');
        Rbac::modelAbility('update.trashed',\App\GroupCategory::class, 'update');
        Rbac::modelAbility('restore',\App\GroupCategory::class);
        Rbac::modelAbility('force-delete', \App\GroupCategory::class);

    });

    Rbac::task('Inspect')
        ->assign('crud.view')
        ->assignTo('ModelInspector','Moderator');

    Rbac::task('Manage')
        ->assign('crud')
        ->assignTo('ModelManager');

    Rbac::task('Clean-up')
        ->assign('trashed:view','trashed:force-delete')
        ->assignTo('DBCleaner');

    Rbac::task('Restore')
        ->assign('trashed:view','trashed:restore')
        ->assignTo('ModelRestorer');

});



Rbac::group('person-addresses:', function() {

    Rbac::crudAbilities(\App\PersonAddress::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});



Rbac::group('person-email-addresses:', function() {

    Rbac::crudAbilities(\App\PersonEmailAddress::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});



Rbac::group('person-phone-numbers:', function() {

    Rbac::crudAbilities(\App\PersonPhoneNumber::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});

