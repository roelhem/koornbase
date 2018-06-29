<?php


Rbac::group('group-categories:', function () {

    Rbac::crudAbilities(\App\GroupCategory::class, 'crud');


    createTrashedAbilities(\App\GroupCategory::class);


    Rbac::task('Inspect')
        ->assign('crud.view')
        ->assignTo('ModelInspector','Moderator');

    Rbac::task('Manage')
        ->assign('crud')
        ->assignTo('ModelManager');

});




Rbac::group('groups:', function () {

    Rbac::crudAbilities(\App\Group::class, 'crud');

    createTrashedAbilities(\App\Group::class);

    Rbac::task('Inspect')
        ->assign('crud.view','group-categories:Inspect')
        ->assignTo(
            'ModelInspector',
            'Moderator',
            \App\Enums\MembershipStatus::NOVICE()->getNode(),
            \App\Enums\MembershipStatus::MEMBER()->getNode(),
            \App\Enums\MembershipStatus::FORMER_MEMBER()->getNode()
        );

    Rbac::task('Manage')
        ->assign('crud', 'group-categories:crud.view')
        ->assignTo('ModelManager');

});





Rbac::group('group-email-addresses:', function() {

    Rbac::crudAbilities(\App\GroupEmailAddress::class);

    Rbac::ability('group-mail-list');

    Rbac::task('Inspect')
        ->assign('crud.view','groups:Inspect')
        ->assignTo(
            'ModelInspector',
            'Moderator',
            \App\Enums\MembershipStatus::NOVICE()->getNode(),
            \App\Enums\MembershipStatus::MEMBER()->getNode()
        );

    Rbac::task('Manage')
        ->assign('crud')
        ->assignTo('ModelManager');
});

