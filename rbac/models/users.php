<?php

Rbac::group('users:', function() {

    Rbac::crudAbilities(\App\User::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

    Rbac::task('ManageOwn')->assign(
        Rbac::gate('Manage|owned', new \App\AuthRules\OwnedModelRule())
            ->assign('Manage')
    )->assignTo('Person');

});

