<?php


Rbac::group('model.', function() {


    // PERSONS

    Rbac::group('persons.', function () {

        $list = Rbac::permission('list')->assignPermission('route.api.persons.index');
        $view = Rbac::permission('view')->assignPermission('route.api.persons.show');
        $create = Rbac::permission('create')->assignPermission('route.api.persons.store');
        $update = Rbac::permission('update')->assignPermission('route.api.persons.update');
        $delete = Rbac::permission('delete')->assignPermission('route.api.persons.destroy');

        $manage = Rbac::permission('manage')
            ->assignPermissions([$list, $view, $create, $update, $delete])
            ->assignToRole('moderator');


        $maintain = Rbac::permission('maintain')
            ->assignPermissions([$view, $update])
            ->assignToRole('commissielid');

        Rbac::permission('maintain|owned')
            ->assignPermission($maintain)
            ->addConstraint('owned')
            ->assignToRole('membership_status.member');



        Rbac::permission('manage|created|no_owner')
            ->assignPermission($manage)
            ->addConstraint('created', ['within' => 125323])
            ->addConstraint('no_owner')
            ->assignToRole('developer');




    });

    Rbac::group('group_categories.', function () {
        Rbac::permission('view.all', 'Groep Categorieën: alle categorieën zichtbaar.')
            ->assignPermission('route.api.group-categories.show');

        Rbac::permission(
            'view.own',
            'Groep Categorieën: alleen de eigen categoriëen zichtbaar.',
            'De gebuiker kan alleen zijn eigen groep-categorieën zien. Dit houdt in dat de gebuiker in een groep moet zitten van deze categorie om meer informatie over deze categorie te tonen.'
        )
            ->assignPermission('route.api.group-categories.show')
            ->assignToRole('membership_status.member');
    });

});


Rbac::role('user')->assignPermission('route.api.me');

