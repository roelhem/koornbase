<?php



function createTrashedAbilities($modelClass) {
    Rbac::modelAbility('trashed.view', $modelClass);
    Rbac::modelAbility('trashed.update',$modelClass);
    Rbac::modelAbility('restore',$modelClass);
    Rbac::modelAbility('force-delete', $modelClass);

    Rbac::task('Clean-up')
        ->assign('trashed.view','force-delete')
        ->assignTo('DBCleaner');

    Rbac::task('Restore')
        ->assign('trashed.view','restore')
        ->assignTo('ModelRestorer');

}


Rbac::group('models:', function() {


    require __DIR__.'/models/users.php';
    require __DIR__.'/models/groups.php';
    require __DIR__.'/models/persons.php';
    require __DIR__.'/models/certificates.php';
    require __DIR__.'/models/memberships.php';
    require __DIR__.'/models/oauth.php';

});