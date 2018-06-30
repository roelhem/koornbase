<?php


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



Rbac::group('persons:', function() {

    Rbac::crudAbilities(\App\Person::class, 'crud');

    createTrashedAbilities(\App\Person::class);

    Rbac::task('Inspect')
        ->assign('crud.view')
        ->assignTo();

    Rbac::task('Manage')
        ->assign('crud')
        ->assign('person-addresses:Manage', 'person-email-addresses:Manage', 'person-phone-numbers:Manage')
        ->assignTo();

    Rbac::task('ManageOwn')
        ->assign(
            Rbac::gate('Manage|owned', new \App\AuthRules\OwnedModelRule())->assign('Manage')
        )->assignTo('Person');

});


