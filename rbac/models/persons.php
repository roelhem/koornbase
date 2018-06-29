<?php


Rbac::group('persons:', function() {

    Rbac::crudAbilities(\App\Person::class, 'crud');

    createTrashedAbilities(\App\Person::class);

    Rbac::task('Inspect')
        ->assign('crud.view')
        ->assignTo('ModelInspector','Moderator');

    Rbac::task('Manage')
        ->assign('crud')
        ->assignTo('ModelManager');

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

