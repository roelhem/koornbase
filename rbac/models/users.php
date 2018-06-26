<?php

Rbac::group('users:', function() {

    Rbac::crudAbilities(\App\User::class, 'crud');

    Rbac::task('Manage')->assign('crud')
        ->assignTo('ModelManager');

});

