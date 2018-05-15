<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 02-05-18
 * Time: 02:28
 */

namespace App\Services\Navigation;


class BreadcrumbService extends NavigationService
{

    protected function init() {
        return [
            'root' => [
                'home',
                'people' => [
                    'people.index',
                ],
                'events' => [
                    'events.index',
                ],
                'auth-manager',
                'statistics',
                'crud' => [
                    'crud.studies' => [
                        'crud.studies.index',
                        'crud.studies.create',
                        'crud.studies.edit',
                        'crud.studies.delete',
                    ],
                    'crud.persons' => [
                        'crud.persons.index',
                        'crud.persons.create',
                        'crud.persons.edit',
                        'crud.persons.delete',
                    ],
                    'crud.budgets' => [
                        'crud.budgets.index',
                        'crud.budgets.create',
                        'crud.budgets.edit',
                        'crud.budgets.delete',
                    ],
                    'crud.events' => [
                        'crud.events.index',
                        'crud.events.create',
                        'crud.events.edit',
                        'crud.events.delete',
                    ],
                    'crud.groups' => [
                        'crud.groups.index',
                        'crud.groups.create',
                        'crud.groups.edit',
                        'crud.groups.delete',
                    ],
                    'crud.group-categories' => [
                        'crud.group-categories.index',
                        'crud.group-categories.create',
                        'crud.group-categories.edit',
                        'crud.group-categories.delete',
                    ],
                ],
                'settings',
                'me',
                'sitemap',
                'develop' => [
                    'develop.nav'
                ]
            ],
            'login',
            'logout',
            'password.request'
        ];
    }

}