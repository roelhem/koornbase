<?php



Rbac::group('memberships:', function() {

    Rbac::crudAbilities(\App\Membership::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});




Rbac::group('koornbeurs-cards:', function() {

    Rbac::crudAbilities(\App\KoornbeursCard::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});
