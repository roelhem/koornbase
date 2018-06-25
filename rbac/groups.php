<?php


Rbac::group('models:',function() {


    Rbac::group('group-categories:', function () {

        Rbac::crudAbilities(\App\GroupCategory::class, 'crud');

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

    Rbac::group('groups:', function () {

        Rbac::crudAbilities(\App\Group::class, 'crud');

        Rbac::group('trashed:', function () {

            Rbac::modelAbility('view.trashed', \App\Group::class, 'view');
            Rbac::modelAbility('update.trashed',\App\Group::class, 'update');
            Rbac::modelAbility('restore',\App\Group::class);
            Rbac::modelAbility('force-delete', \App\Group::class);

        });

        Rbac::task('Inspect')
            ->assign('crud.view','group-categories:Inspect')
            ->assignTo(
                'ModelInspector',
                'Moderator',
                'membership_status.Novice',
                'membership_status.Member',
                'membership_status.FormerMember'
            );

        Rbac::task('Manage')
            ->assign('crud', 'group-categories:crud.view')
            ->assignTo('ModelManager');

        Rbac::task('Clean-up')
            ->assign('trashed:view','trashed:force-delete')
            ->assignTo('DBCleaner');

        Rbac::task('Restore')
            ->assign('trashed:view','trashed:restore')
            ->assignTo('ModelRestorer');

    });


    Rbac::group('group-email-addresses:', function() {

        Rbac::crudAbilities(\App\GroupEmailAddress::class);

        Rbac::ability('group-mail-list');

        Rbac::task('Inspect')
            ->assign('crud.view','groups:Inspect')
            ->assignTo(
                'ModelInspector',
                'Moderator',
                'membership_status.Novice',
                'membership_status.Member'
            );

        Rbac::task('Manage')
            ->assign('crud')
            ->assignTo('ModelManager');
    });


});